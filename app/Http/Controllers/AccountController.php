<?php

namespace App\Http\Controllers;


use Campaign;
use User;
use Auth;

use View;

class AccountController extends Controller {


	public function index(){
        $my_campaigns = Auth::user()->campaigns()->get();
        return View::make('home.list',[
            'mycampaigns' => $my_campaigns->toJson() 
        ]);
	}

	public function stats(){
		return View::make('home.index');
	}
}
