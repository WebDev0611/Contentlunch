<?php

namespace App\Http\Controllers;

use View;
use Auth;

class SettingsController extends Controller {

	public function index(){
		return View::make('settings.index');
	}

	public function content(){
		return View::make('settings.content');

	}

	public function connections(){
		return View::make('settings.connections');

	}

	public function seo(){
		return View::make('settings.seo');

	}

	public function buying(){
		return View::make('settings.buying');

	}

}
