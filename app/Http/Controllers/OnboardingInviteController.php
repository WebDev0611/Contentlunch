<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use View;
use Socialite;

class OnboardingInviteController extends Controller
{
    public function invite() {
        return View::make('onboarding.invite');
    }
    public function redirect()
    {
        return Socialite::driver('facebook')->scopes(['user_friends'])->redirect();
    }

    public function callback()
    {
    	//$providerUser = \Socialite::driver('facebook')->user();
    	$providerUser = \Socialite::driver('facebook')->user();

    	dd($providerUser);
        // when facebook call us a with token
    }
}
