<?php

namespace App\Http\Controllers;

use View;

class AgencyController extends Controller {

	public function index(){
		return View::make('agency.index');
	}

}
