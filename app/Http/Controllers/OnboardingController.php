<?php

namespace App\Http\Controllers;

use View;

class OnboardingController extends Controller
{

    public function signup() {
        return View::make('onboarding.signup');
    }

/*    public function invite() {
        return View::make('onboarding.invite');
    }*/

    public function score() {
        return View::make('onboarding.score');
    }

    public function connect(){
        return View::make('onboarding.connect');
    }
}
