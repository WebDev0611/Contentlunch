<?php

namespace App\Http\Controllers;

use View;

class AccountController extends Controller {


	public function index(){
		return View::make('home.index');
	}

	public function tasks(){
		return View::make('home.list');
	}
}
