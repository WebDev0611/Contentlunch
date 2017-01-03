<?php

namespace App\Http\Controllers;

use \Illuminate\Http\Request;
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
        $hasTwitter = (boolean) Auth::user()->connectionsBySlug('twitter')->count();
        $hasFacebook = (boolean) Auth::user()->connectionsBySlug('facebook')->count();
        $hasWordPress = (boolean) Auth::user()->connectionsBySlug('wordpress')->count();

        return View::make('onboarding.connect',
            compact('hasTwitter', 'hasFacebook', 'hasWordPress')
        );
    }

    public function signupWithInvite(AccountInvite $invite)
    {
        if ($invite->isUsed()) {
            return View::make('onboarding.invite_used');
        }

        return View::make('onboarding.invite_signup', compact('invite'));
    }

    public function createWithInvite(InvitedAccountRequest $request)
    {
        $invite = AccountInvite::find($request->invite_id);
        $user = $this->createInvitedUser($invite, $request);

        $this->createNewUserSession($user);

        return redirect('/')->with([
            'flash_message' => 'Welcome to ContentLaunch! You\'re now part of the ' . $invite->account->name . ' account!',
            'flash_message_type' => 'success',
            'flash_message_important' => true
        ]);
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
            'name' => $request->input('name'),
            'password' => $request->input('password'),
            'email' => $request->input('email'),
        ]);
    }
}
