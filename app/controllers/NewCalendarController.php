<?php

class NewCalendarController extends BaseController {

	public function index(){
		return View::make('2016.calendar.index');
	}

	public function campaigns(){
		return View::make('2016.calendar.campaigns');
	}
}
