<?php

namespace App\Http\Controllers;

use View;

use App\Idea;

use App\IdeaContent;

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

	public function editor($id = 0){
		$idea = Idea::where(['id' => $id ])
					->first();
					

		return View::make('plan.editor', ['name' => $idea->name, 'text' => $idea->text, 'tags' => $idea->tags]);
	}
	
	public function ideas(){
		return View::make('plan.ideas');
	}

	public function parked(){
		return View::make('plan.parked');
	}
}
