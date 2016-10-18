<?php

namespace App\Http\Controllers;

use View;
use App\User;
use App\Account;
use App\AccountInvite;
use \Illuminate\Http\Request;
use Hash;

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

    public function createWithInvite(Request $request)
    {
        dd($request->input());
    }
}
