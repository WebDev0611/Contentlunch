<?php

namespace App\Http\Controllers;

use App\Http\Requests\Content\ContentRequest;
use App\ContentType;
use App\BuyingStage;
use App\Connection;
use App\Campaign;
use App\Content;
use App\User;
use App\Tag;
use View;
use Auth;

class ContentController extends Controller {

	public function index(){
		return View::make('content.index');
	}

	public function create(){
		return View::make('content.create');	
	}

	public function edit(){

		// - Create Content Type Drop Down Data
		$contenttypedd = ['' => '-- Select Content Type --'];
		$contenttypedd += ContentType::select('id','name')->orderBy('name', 'asc')->distinct()->lists('name', 'id')->toArray();
		// - Create Author Drop Down Data
		//  ---- update sql query to pull ONLY team members once that is added
		$authordd = ['' => '-- Select Author --'];
		$authordd += User::select('id','name')->orderBy('name', 'asc')->distinct()->lists('name', 'id')->toArray();

		// - Create Connections Drop Down Data
		$connectionsdd = ['' => '-- Select Destination --'];
		$connectionsdd += Connection::select('id','name')->where('active',1)->orderBy('name', 'asc')->distinct()->lists('name', 'id')->toArray();

		// - Create Tags Drop Down Data
		$tagsdd = Tag::select('id','tag')->orderBy('tag', 'asc')->distinct()->lists('tag', 'id')->toArray();

		// - Create Related Content Drop Down Data
		$relateddd = ['' => '-- Select Tags --'];
		$relateddd = Content::select('id','title')->orderBy('title', 'asc')->distinct()->lists('title', 'id')->toArray();

		// - Create Related Content Drop Down Data
		$stageddd = ['' => '-- Select a Buying Stage --'];
		$stageddd += BuyingStage::select('id','name')->orderBy('name', 'asc')->distinct()->lists('name', 'id')->toArray();

		// - Create Campaign Drop Down Data
		$campaigndd = ['' => '-- Select an Campaign --'];
		$campaigndd += Auth::user()->campaigns()->select('id','title')->where('status',1)->orderBy('title', 'asc')->distinct()->lists('title', 'id')->toArray();


		return View::make('content.editor', compact('contenttypedd', 'authordd', 'connectionsdd', 'tagsdd', 'relateddd', 'stageddd', 'campaigndd'));	
	}
	public function editStore(ContentRequest $request) {

		$content = new Content;
		$content->title = $request->input('title');
		$content->body = $request->input('content');
		$content->due_date = $request->input('due_date');
		$content->meta_title = $request->input('meta_title');
		$content->meta_keywords = $request->input('meta_keywords');
		$content->meta_description = $request->input('meta_descriptor');
		$content->save();

		// - Attach to the user
		Auth::user()->contents()->save($content);
		// - Save Campaign
		$campaign = Campaign::find($request->input('campaign'));
		$campaign->contents()->save($content);
		// - Save Content Type
		$conType = ContentType::find($request->input('content_type'));
		$conType->contents()->save($content);
		// - Save connection
		$connection = Connection::find($request->input('connections'));
		$connection->contents()->save($content);
		// Attach authors
		$content->authors()->attach($request->input('author'));
		// Attach Tags
		$content->tags()->attach($request->input('tags'));

		dd($request->all());

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
