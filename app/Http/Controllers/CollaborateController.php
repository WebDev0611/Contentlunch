<?php

namespace App\Http\Controllers;

use View;

class CollaborateController extends Controller {

	public function index(){
		return View::make('collaborate.index');
	}

}
