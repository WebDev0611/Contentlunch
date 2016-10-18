<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\emailInviteRequest;
use View;
use Mail;
use Auth;

class OnboardingInviteController extends Controller
{
    public function invite()
    {
        return View::make('onboarding.invite');
    }

    public function emailInvite(emailInviteRequest $request)
    {
        $emails = $request->input('emails');

        $data = [
            'name' => Auth::user()->name,
            'emails' =>  explode(',', $emails)
        ];

        Mail::send('emails.invite.email_invite', $data, function($message)  use ($data) {
            $message->from("invites@contentlaunch.com", "Content Launch")
                ->to('noreply@contentlaunch.com')
                ->bcc($data['emails'])
                ->subject('Check Out Content Launch');
        });

        return redirect()->route('inviteIndex')->with([
            'flash_message' => 'You have sent ' . count($data['emails']) .' invite(s) about content launch out. Thanks!.',
            'flash_message_type' => 'success',
            'flash_message_important' => true
        ]);
    }

}
