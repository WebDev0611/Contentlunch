<?php

namespace App\Http\Controllers;

use View;

class AnalyzeController extends Controller {

	public function index(){
		return View::make('analyze.index');
	}

}
