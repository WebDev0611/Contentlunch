<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use View;
use Mail;
use Auth;

use App\Http\Requests\emailInviteRequest;
use App\AccountInvite;

class OnboardingInviteController extends Controller
{
    public function invite()
    {
        return View::make('onboarding.invite');
    }

    public function emailInvite(emailInviteRequest $request)
    {
        $emails = collect(explode(',', $request->input('emails')))
            ->map(function($email) {
                return trim($email);
            })
            ->toArray();

        $account = Auth::user()->account;

        foreach ($emails as $email) {
            $link = $this->createInviteUrl($email, $account);

            Mail::send('emails.invite.email_invite', compact('link'), function($message) use ($email) {
                $message->from("invites@contentlaunch.com", "Content Launch")
                    ->to($email)
                    ->subject('Check Out Content Launch');
            });
        }

        return redirect()->route('inviteIndex')->with([
            'flash_message' => 'You have sent ' . count($emails) . ' invite(s) about content launch out. Thanks!',
            'flash_message_type' => 'success',
            'flash_message_important' => true
        ]);
    }

    private function createInviteUrl($email, $account)
    {
        $accountInvite = AccountInvite::create([
            'email' => $email,
            'account_id' => $account->id,
        ]);

        return route('signupWithInvite', $accountInvite);
    }
}
