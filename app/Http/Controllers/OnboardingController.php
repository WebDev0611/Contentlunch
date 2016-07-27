<?php

namespace App\Http\Controllers;

use View;
use App\User;
use \Illuminate\Http\Request;
use Hash;

class OnboardingController extends Controller
{

    public function signup() {
        $user = new User;
        return view('onboarding.signup')->with(compact('user'));
    }

    /*public function invite() {
        return View::make('onboarding.invite');
    }*/

    public function score() {
        return View::make('onboarding.score');
    }

    public function connect(){
        return View::make('onboarding.connect');
    }

}
