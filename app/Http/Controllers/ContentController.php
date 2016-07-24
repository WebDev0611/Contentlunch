<?php

namespace App\Http\Controllers;

use App\Http\Requests\Content\ContentRequest;
use Illuminate\Support\Facades\File;
use App\ContentType;
use App\BuyingStage;
use App\Connection;
use App\Attachment;
use App\Campaign;
use App\Content;
use App\User;
use App\Tag;
use Storage;
use View;
use Auth;

class ContentController extends Controller {

	public function index(){
		$countContent =  Auth::user()->contents()->count();
		$published =  Auth::user()->contents()->where('published',1)->get();
		$readyPublished = Auth::user()->contents()->where('ready_published',1)->get();
		$written = Auth::user()->contents()->where('written',1)->get();
		return View::make('content.index', compact('published', 'readyPublished', 'written', 'countContent'));
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
		$authordd = User::select('id','name')->orderBy('name', 'asc')->distinct()->lists('name', 'id')->toArray();

		// - Create Connections Drop Down Data
		$connectionsdd = ['' => '-- Select Destination --'];
		$connectionsdd += Connection::select('id','name')->where('active',1)->orderBy('name', 'asc')->distinct()->lists('name', 'id')->toArray();

		// - Create Tags Drop Down Data
		$tagsdd = Tag::select('id','tag')->orderBy('tag', 'asc')->distinct()->lists('tag', 'id')->toArray();

		// - Create Related Content Drop Down Data
		$stageddd = ['' => '-- Select a Buying Stage --'];
		$stageddd += BuyingStage::select('id','name')->orderBy('name', 'asc')->distinct()->lists('name', 'id')->toArray();

		// - Create Campaign Drop Down Data
		$campaigndd = ['' => '-- Select an Campaign --'];
		$campaigndd += Auth::user()->campaigns()->select('id','title')->where('status',1)->orderBy('title', 'asc')->distinct()->lists('title', 'id')->toArray();

		// - Create Related Drop Down Data
		$relateddd = ['' => '-- Select an Campaign --'];
		$relateddd = Auth::user()->contents()->select('id','title')->orderBy('title', 'asc')->distinct()->lists('title', 'id')->toArray();


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
		$content->written = 1;
		$content->save();

		// - Attach to the user
		Auth::user()->contents()->save($content);
		// IF compaign lets attach it
		if($request->input('campaign')){
			// - Save Campaign
			$campaign = Campaign::find($request->input('campaign'));
			$campaign->contents()->save($content);
		}
		// - Attach the related data
		if($request->input('related')){
			$content->related()->attach($request->input('related'));
		}
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
		// - Images
		if($request->hasFile('images'))
		{
			foreach( $request->file('images') as $image ) {
				$filename   	= $image->getClientOriginalName();
				$extension  	= $image->getClientOriginalExtension();
				$mime       	= $image->getClientMimeType();
				$fileDoc    	= time().'_'.$filename;
				$path = 'attachment/'.Auth::id().'/images/';
				$fullPath = $path.$fileDoc;
				Storage::put($fullPath,  File::get($image));

				// - Caputure the upload
				$attachment              	    = new Attachment;
				$attachment->filepath     = $path;
				$attachment->type   	    = 'image';
				$attachment->filename   = $filename;
				$attachment->extension = $extension;
				$attachment->mime   	    = $mime;
				$attachment->save();
				// attach image to content
				$content->attachments()->save($attachment);
			}
		}
		// - File Attachments
		if($request->hasFile('files'))
		{
			foreach( $request->file('files') as $file ) {
				$filename   	= $file->getClientOriginalName();
				$extension  	= $file->getClientOriginalExtension();
				$mime       	= $file->getClientMimeType();
				$fileDoc    	= time().'_'.$filename;
				$path = 'attachment/'.Auth::id().'/files/';
				$fullPath = $path.$fileDoc;
				Storage::put($fullPath,  File::get($file));

				// - Caputure the upload
				$attachment              	    = new Attachment;
				$attachment->filepath     = $path;
				$attachment->type   	    = 'file';
				$attachment->filename   = $filename;
				$attachment->extension = $extension;
				$attachment->mime   	    = $mime;
				$attachment->save();
				// attach image to content
				$content->attachments()->save($attachment);
			}
		}

		// - Lets get out of here
		return redirect()->route('contentIndex')->with([
		    'flash_message' => 'You have created content titled '.$content->title.'.',
		    'flash_message_type' => 'success',
		    'flash_message_important' => true
		]);

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
