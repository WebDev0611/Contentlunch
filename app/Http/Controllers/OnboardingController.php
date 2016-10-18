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

class OnboardingController extends Controller
{
    public function signup()
    {
        $user = new User;
        return view('onboarding.signup')->with(compact('user'));
    }

    /*public function invite() {
        return View::make('onboarding.invite');
    }*/

    public function score()
    {
        return View::make('onboarding.score');
    }

    public function connect()
    {
        return View::make('onboarding.connect');
    }

    public function signupWithInvite(AccountInvite $invite)
    {
        if ($invite->isUsed()) {
            throw new \NotFoundHttpException;
        }

        $data = [
            'invite' => $invite
        ];

        return View::make('onboarding.invite_signup', $data);
    }

    public function createWithInvite(InvitedAccountRequest $request)
    {
        $user = User::create([
            'name' => $request->input('name'),
            'password' => bcrypt($request->input('password')),
            'email' => $request->input('email'),
            'account_id' => $request->input('account_id')
        ]);

        Auth::login($user);

        $account = Account::find($request->account_id)->first();

        return redirect('/')->with([
            'flash_message' => 'Welcome to ContentLaunch! You\'re now part of the ' . $account->name . ' account!',
            'flash_message_type' => 'success',
            'flash_message_important' => true
        ]);
    }
}
