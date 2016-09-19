<?php

namespace App\Http\Controllers;


use Campaign;
use User;
use Auth;

use View;

class AccountController extends Controller {


	public function index(){
        $my_campaigns = Auth::user()->campaigns()->get();
        $my_tasks = Auth::user()->tasks()->get();

        return View::make('home.list',[
            'mycampaigns' => $my_campaigns->toJson(),
            'tasks' => $my_tasks->toJson()
        ]);
	}

	public function stats(){
		$my_campaigns = Auth::user()->campaigns()->get();
        $my_tasks = Auth::user()->tasks->get();

		return View::make('home.index',[
            'mycampaigns' => $my_campaigns->toJson() 
        ]);
	}
}
