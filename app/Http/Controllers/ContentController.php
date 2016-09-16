<?php

namespace App\Http\Controllers;

use App\Http\Requests\Content\ContentRequest;
use Launch\Connections\API\WordpressAPI;
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
use Twitter;
use Session;

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


	public function publish(Content $content)  {
		// - this will need to be dynamic ( database provider table? )
		// -- Once we hook up another API i will know how i should organize this
		$class = 'Connections\API\\'.$content->connection->provider->class_name;
		$create = (new $class($content))->createPost();

		$content->published = 1;
		$content->ready_published = 0;
		$content->written = 0;
		$content->save();

		// - Lets get out of here
		return redirect()->route('contentIndex')->with([
		    'flash_message' => "You have published ".$content->title." to " . $content->connection->provider->slug,
		    'flash_message_type' => 'success',
		    'flash_message_important' => true
		]);
	}

	// this is technically create content
	public function createContent() {

		$tagsdd = Tag::dropdown();
		$authordd = User::dropdown();
		$relateddd = Content::dropdown();
		$stageddd = BuyingStage::dropdown();
		$campaigndd = Campaign::dropdown();
		$connectionsdd = Connection::dropdown();
		$contenttypedd = ContentType::dropdown();

		return View::make('content.editor', compact('contenttypedd', 'authordd', 'connectionsdd', 'tagsdd', 'relateddd', 'stageddd', 'campaigndd'));
	}

	// - edit content on page
	public function editContent(Content $content) {

		$tagsdd = Tag::dropdown();
		$authordd = User::dropdown();
		$relateddd = Content::dropdown();
		$stageddd = BuyingStage::dropdown();
		$campaigndd = Campaign::dropdown();
		$connectionsdd = Connection::dropdown();
		$contenttypedd = ContentType::dropdown();

		return View::make('content.editor', compact('content', 'contenttypedd', 'authordd', 'connectionsdd', 'tagsdd', 'relateddd', 'stageddd', 'campaigndd'));

	}

	public function editStore(ContentRequest $request, $id = null) {
		// - Default to creating a new Content
		$content = new Content;

		if (is_numeric($id)) {
			$content = Content::find($id);
			$content->touch();
			// - Remove all related, will attach back ( keeps pivot table clean )
			$content->related()->detach();
			$content->tags()->detach();
			$content->authors()->detach();
		}

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
        $contentType = $request->input('content_type');
        $tweetContentType = ContentType::where('name', 'Tweet')->first();
		$conType = ContentType::find($contentType);
		$conType->contents()->save($content);

		// - Save connection
		$connection = Connection::find($request->input('connections'));
		$connection->contents()->save($content);

        if ($tweetContentType->id == $contentType) {
            $this->publishToTwitter($content->body, $connection);
        }

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

    private function publishToTwitter($message, $connection)
    {
        $settings = $connection->getSettings();

        $requestToken = [
            'token' => $settings->oauth_token,
            'secret' => $settings->oauth_token_secret,
            'consumer_key' => env('TWITTER_CONSUMER_KEY'),
            'consumer_secret' => env('TWITTER_CONSUMER_SECRET'),
        ];

        $message = strip_tags($message);

        Session::forget('access_token');
        Twitter::reconfig($requestToken);

        try {
            Twitter::postTweet([ 'status' => $message ]);
        }
        catch (Exception $e) {
            $flashMessage  = "We couldn't post the content to Twitter using the connection [" . $settings->name . "]. ";
            $flashMessage .= "Please make sure the connection is properly configured before trying again.";

            return redirect()->route('editIndex')->with([
                'flash_message' => $flashMessage,
                'flash_message_type' => 'danger',
                'flash_message_important' => true
            ]);
        }
    }

	public function get_written($step = 1) {

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
