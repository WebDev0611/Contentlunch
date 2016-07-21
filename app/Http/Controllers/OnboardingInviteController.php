<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\emailInviteRequest;
use View;
use Mail;
use Auth;

class OnboardingInviteController extends Controller
{
    public function invite() {
        return View::make('onboarding.invite');
    }
    public function emailInvite(emailInviteRequest $request)
    {
        $emails = $request->input('emails');
       // - format data
        $data = [
            'name' => Auth::user()->name,
            'emails' =>  explode(',', $emails)
        ];


        Mail::send('emails.invite.email_invite', $data, function($message)  use ($data) {
                $message->from("invites@contentlaunch.com", "Content Launch")->to('noreply@contentlaunch.com')
                ->bcc($data['emails'])
                ->subject('Check Out Content Launch');
            });

/*
        Mail::send('secure.emails.agent_account_creation', $data, function($message) use ($data)
        {
            $message->from(env('EMAIL_FROM'), env('EMAIL_TITLE'));
            $message->to($data['email']);
            $message->subject(env('EMAIL_TITLE') . ' Account Creation');

        });*/


        return redirect()->route('inviteIndex')->with([
            'flash_message' => 'You have sent ' . count($data['emails']) .' invite(s) about content launch out. Thanks!.',
            'flash_message_type' => 'success',
            'flash_message_important' => true
        ]);
    }

}
