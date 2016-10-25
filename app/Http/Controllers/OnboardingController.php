<?php

namespace App\Http\Controllers;

use \Illuminate\Http\Request;
use View;
use Hash;
use Auth;

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
        return view('onboarding.signup')->with(compact('user'));
    }

    public function signupPhotoUpload(Request $request)
    {
        if ($request->hasFile('avatar')) {
            $filename = str_random(16);
            $path = 'attachment/_tmp/';
            $imageUrl = Helpers::handleUpload($request->file('avatar'), $filename, $path);

            return response()->json([ 'image' => $imageUrl ]);
        } else {
            return response()->json([ 'error' => true, 'message' => 'File not sent correctly' ], 400);
        }

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
        $user = $this->createInvitedUser($request);
        Auth::login($user);

        $this->markInviteAsUsed($request, $user);

        $account = Account::find($request->account_id)->first();

        return redirect('/')->with([
            'flash_message' => 'Welcome to ContentLaunch! You\'re now part of the ' . $account->name . ' account!',
            'flash_message_type' => 'success',
            'flash_message_important' => true
        ]);
    }

    private function markInviteAsUsed($request, $user)
    {
        $invite = AccountInvite::find($request->invite_id);
        $invite->user()->associate($user);
        $invite->save();
    }

    private function createInvitedUser($request)
    {
        return User::create([
            'name' => $request->input('name'),
            'password' => bcrypt($request->input('password')),
            'email' => $request->input('email'),
            'account_id' => $request->input('account_id')
        ]);
    }
}
