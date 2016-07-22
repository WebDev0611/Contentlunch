<?php

namespace App\Http\Controllers;

use View;

class ContentController extends Controller {

	public function index(){
		return View::make('content.index');
	}

	public function create(){
		return View::make('content.create');	
	}

	public function edit(){
		return View::make('content.editor');	
	}

	public function get_written($step = 1){

		//need to do proper form validation, etc. 
		//this is just to get the UI spit out
		
		if($step == 1){
			return View::make('content.get_written_1');	
		}
		if($step == 2){
			return View::make('content.get_written_2');	
		}
		if($step == 3){
			return View::make('content.get_written_3');		
		}
	}

}
