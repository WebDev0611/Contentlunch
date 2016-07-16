<?php

namespace App\Http\Controllers;

use View;

class ContentController extends Controller {

	public function index(){
		return View::make('content.index');
	}

}
