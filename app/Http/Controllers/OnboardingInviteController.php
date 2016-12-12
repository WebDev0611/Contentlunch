<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Mail;
use Auth;

use App\Http\Requests\emailInviteRequest;
use App\AccountInvite;
use App\Account;
use App\User;

class OnboardingInviteController extends Controller
{
    public function invite()
    {
        return view('onboarding.invite');
    }

    public function emailInvite(emailInviteRequest $request)
    {
        $emails = $this->emailsFromRequest($request);

        foreach ($emails as $email) {
            $this->sendInvite($email);
        }

        if ($request->ajax()) {
            $feedback = response()->json([ 'success' => true ], 201);
        } else {
            $feedback = redirect()->route('inviteIndex')->with([
                'flash_message' => 'You have sent ' . count($emails) . ' invite(s) about content launch out. Thanks!',
                'flash_message_type' => 'success',
                'flash_message_important' => true
            ]);
        }

        return $feedback;
    }

    private function sendInvite($email)
    {
        $link = $this->createInviteUrl($email);

        Mail::send('emails.invite.email_invite', compact('link'), function($message) use ($email) {
            $message->from("invites@contentlaunch.com", "Content Launch")
                ->to($email)
                ->subject('Check Out Content Launch');
        });
    }

    private function emailsFromRequest($request)
    {
        return collect(explode(',', $request->input('emails')))
            ->map(function($email) {
                return trim($email);
            })
            ->toArray();
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
