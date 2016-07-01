<?php

class NewPlanController extends BaseController {

	public function index(){
		return View::make('2016.plan.index');
	}

	public function trends(){
		return View::make('2016.plan.trends');
	}

	public function prescription(){
		return View::make('2016.plan.prescription');
	}

	public function editor(){
		return View::make('2016.plan.editor');
	}
	
	public function ideas(){
		return View::make('2016.plan.ideas');
	}
}
