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
		
		//need to check against account info
		$idea = Idea::where(['id' => $id ])
					->first();

		$idea_content = IdeaContent::where(['idea_id'=> $id])->get();
					

		return View::make('plan.editor', ['name' => $idea->name, 
										'text' => $idea->text, 
										'tags' => $idea->tags, 
										'contents' => $idea_content ]);
	}
	
	public function ideas(){
		return View::make('plan.ideas');
	}

	public function parked(){
		return View::make('plan.parked');
	}
}
