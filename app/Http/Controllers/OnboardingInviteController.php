<?php

namespace App\Http\Controllers;

use App\Account;
use App\AccountInvite;
use App\Http\Requests\emailInviteRequest;
use App\Limit;
use App\User;
use Auth;
use Illuminate\Http\Request;
use Mail;

class OnboardingInviteController extends Controller
{
    public function invite()
    {
        return view('onboarding.invite');
    }

    public function emailInvite(emailInviteRequest $request)
    {
        $emails = $this->emailsFromRequest($request);

        if (Auth::user()->cant('invite', [ Account::selectedAccount(), $emails ])) {
            return $this->unauthorizedFeedback($request);
        }

        foreach ($emails as $email) {
            $this->sendInvite($email);
        }

        return $this->successFeedback($request, $emails);
    }

    private function emailsFromRequest($request)
    {
        return collect(explode(',', $request->input('emails')))
            ->map(function($email) { return trim($email); })
            ->toArray();
    }

    private function sendInvite($email)
    {
        $data = [
            'link' => $this->createInviteUrl($email),
            'user' => Auth::user(),
            'account' => Account::selectedAccount(),
        ];

        Mail::send('emails.invite.email_invite', $data, function($message) use ($email) {
            $message->from("invites@contentlaunch.com", "Content Launch")
                ->to($email)
                ->subject('You\'ve been invited to Content Launch');
        });
    }

    private function createInviteUrl($email)
    {
        $account = Account::selectedAccount();
        $accountInvite = AccountInvite::create([
            'email' => $email,
            'account_id' => $account->id,
        ]);

        return route('signupWithInvite', $accountInvite);
    }

    protected function unauthorizedFeedback($request)
    {
        $message = Limit::feedbackMessage('users_per_account');
        $feedback = redirect()->route('inviteIndex')->with([
            'flash_message' => $message,
            'flash_message_type' => 'danger',
            'flash_message_important' => true,
        ]);

        if ($request->ajax()) {
            $feedback = response()->json([ 'data' => $message ], 403);
        }

        return $feedback;
    }

    protected function successFeedback($request, $emails)
    {
        $feedback = response()->json([ 'success' => true ], 201);

        if (!$request->ajax()) {
            $feedback = redirect()->route('inviteIndex')->with([
                'flash_message' => 'You have sent ' . count($emails) . ' invite(s) about content launch out. Thanks!',
                'flash_message_type' => 'success',
                'flash_message_important' => true
            ]);
        }

        return $feedback;
    }

    public function inviteUser(Request $request, User $user)
    {
        $account = Account::selectedAccount();
        $this->sendInvite($user->email);

        return redirect()->route('dashboard')->with([
            'flash_message' => 'An invite has been sent to ' . $user->name . ' to join the account ' . $account->name . '.',
            'flash_message_type' => 'success',
            'flash_message_important' => true
        ]);
    }
}
