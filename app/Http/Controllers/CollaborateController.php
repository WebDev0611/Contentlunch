<?php

namespace App\Http\Controllers;

use View;

class CollaborateController extends Controller {

	public function index(){
		return View::make('collaborate.index');
	}

	public function linkedin(){
		return View::make('collaborate.linkedin');
	}

	public function twitter(){
		return View::make('collaborate.twitter');
	}

	public function bookmarks(){
		return View::make('collaborate.bookmarks');
	}
}
