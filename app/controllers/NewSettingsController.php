<?php

class NewSettingsController extends BaseController {

	public function index(){
		return View::make('2016.settings.index');
	}

	public function content(){
		return View::make('2016.settings.content');

	}

	public function connections(){
		return View::make('2016.settings.connections');

	}

	public function seo(){
		return View::make('2016.settings.seo');

	}

	public function buying(){
		return View::make('2016.settings.buying');

	}

}
