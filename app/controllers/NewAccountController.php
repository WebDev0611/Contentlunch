<?php

class NewAccountController extends BaseController {

	public function index(){
		return View::make('2016.home.index');
	}

	public function tasks(){
		return View::make('2016.home.list');
	}
}
