<?php

namespace App\Http\Controllers;

use \Illuminate\Http\Request;
use Mail;
use View;
use Hash;
use Auth;
use Session;

use App\User;
use App\Account;
use App\AccountInvite;
use App\Http\Requests\Onboarding\InvitedAccountRequest;
Use App\Helpers;

class OnboardingController extends Controller
{
    public function signup()
    {
        $user = new User;
        $avatarUrl = session('avatar_temp_url');

        return view('onboarding.signup', compact('user', 'avatarUrl'));
    }

    public function signupPhotoUpload(Request $request)
    {
        $response = response()->json([ 'error' => true, 'message' => 'File not sent correctly' ], 400);

        if ($request->hasFile('avatar')) {
            $response = [
                'image' => Helpers::handleTmpUpload($request->file('avatar'))
            ];
        }

        return $response;
    }

    public function score()
    {
        return View::make('onboarding.score');
    }

    public function connect()
    {
        $account = Account::selectedAccount();

        return view('onboarding.connect', [
           'hasTwitter' =>  (boolean) $account->connectionsBySlug('twitter')->count(),
           'hasFacebook' =>  (boolean) $account->connectionsBySlug('facebook')->whereActive(true)->count(),
           'hasWordPress' =>  (boolean) $account->connectionsBySlug('wordpress')->count(),
           'hasHubspot' =>  (boolean) $account->connectionsBySlug('hubspot')->count(),
           'hasMailchimp' =>  (boolean) $account->connectionsBySlug('mailchimp')->count(),
           'hasLinkedIn' =>  (boolean) $account->connectionsBySlug('linkedin')->count(),
           'hasDropbox' =>  (boolean) $account->connectionsBySlug('dropbox')->count(),
           'hasGoogleDrive' =>  (boolean) $account->connectionsBySlug('google-drive')->count(),
        ]);
    }

    public function signupWithInvite(Request $request, AccountInvite $invite)
    {
        if ($invite->isUsed()) {
            return view('onboarding.invite_used');
        } else if ((new User)->cant('join', $invite->account)) {
            $this->notifyUserCountExceeded($invite, $invite->account);
            return view('errors.account_full');
        } else if (Auth::check()) {
            return $this->useInviteForLoggedUser($invite);
        }

        return view('onboarding.invite_signup', compact('invite'));
    }

    protected function useInviteForLoggedUser(AccountInvite $invite)
    {
        $userBelongsToAccount = (boolean) $invite->account->users()->find(Auth::id());

        if ($userBelongsToAccount) {
            $message = "You are already a member of the {$invite->account->name} account.";
            $type = "danger";
        } else {
            $message = "You're now part of the {$invite->account->name} account.";
            $type = "success";
            $invite->attachUser(Auth::user());
        }

        return redirect('/')->with([
            'flash_message' => $message,
            'flash_message_type' => $type,
            'flash_message_important' => true,
        ]);
    }

    public function createWithInvite(InvitedAccountRequest $request)
    {
        $invite = AccountInvite::find($request->invite_id);
        $account = $invite->account;

        if ((new User)->cant('join', $account)) {
            $this->notifyUserCountExceeded($invite, $account);
            abort(404);
        }

        $user = $this->createInvitedUser($invite, $request);

        $this->createNewUserSession($user);

        return redirect('/')->with([
            'flash_message' => "Welcome to ContentLaunch! You're now part of the {$invite->account->name} account!",
            'flash_message_type' => 'success',
            'flash_message_important' => true
        ]);
    }

    protected function notifyUserCountExceeded(AccountInvite $invite, Account $account)
    {
        $sendTo = $account->proxyToParent()->users->first();

        Mail::send('emails.exceeded_user_count', [ 'email' => $invite->email ], function ($message) use ($sendTo) {
            $message->from('no-reply@contentlaunch.com', 'Content Launch')
                ->to($sendTo->email)
                ->subject('User limit reached');
        });
    }

    protected function createNewUserSession($user)
    {
        Auth::logout();
        Session::flush();
        Auth::login($user);
    }

    private function createInvitedUser(AccountInvite $invite, $request)
    {
        return $invite->createUser([
            'name' => $request->name,
            'password' => $request->password,
            'email' => $request->email,
        ]);
    }
}
