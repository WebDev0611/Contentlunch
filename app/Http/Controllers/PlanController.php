<?php

namespace App\Http\Controllers;

use View;

class PlanController extends Controller {

	public function index(){
		return View::make('plan.index');
	}

	public function trends(){
		return View::make('plan.trends');
	}

	public function prescription(){
		return View::make('plan.prescription');
	}

	public function editor(){
		return View::make('plan.editor');
	}
	
	public function ideas(){
		return View::make('plan.ideas');
	}

	public function parked(){
		return View::make('plan.parked');
	}
}
