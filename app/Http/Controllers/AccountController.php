<?php

namespace App\Http\Controllers;

use View;

class AccountController extends Controller {


	public function index(){
		return View::make('home.list');
	}

	public function stats(){
		return View::make('home.index');
	}
}
