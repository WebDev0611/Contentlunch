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
use Carbon\Carbon;
use Illuminate\Http\Request;
use Input;


class ContentController extends Controller {

    public function index()
    {
        $countContent = Auth::user()->contents()->count();
        $published = Auth::user()->contents()->where('published',1)->orderBy('updated_at', 'desc')->get();
        $readyPublished = Auth::user()->contents()->where('ready_published',1)->orderBy('updated_at', 'desc')->get();
        $written = Auth::user()->contents()->where('written',1)->orderBy('updated_at', 'desc')->get();
        $connections = Auth::user()->connections()->where('active', 1)->get();

        return View::make('content.index', compact('published', 'readyPublished', 'written', 'countContent', 'connections'));
    }

	public function store(Request $req)
    {
		$content = new Content;

		$content->title = $req->input('title');
		$content->content_type_id = $req->input('content_type');
		$content->save();

		// - Attach to the user
		Auth::user()->contents()->save($content);
		return redirect('edit/' . $content->id );
	}

    public function create()
    {
		//$my_campaigns = Campaign::
        $my_campaigns = Auth::user()->campaigns()->get();

        return View::make('content.create',[
            'contenttypedd' => ContentType::dropdown(),
            'campaigndd' => Campaign::dropdown()
        ]);
    }

    public function directPublish(Request $request, $contentId)
    {
        $content = Content::find($contentId);
        $connections = collect(explode(',', $request->input('connections')))
            ->map(function($connectionId) {
                return Connection::find($connectionId);
            });

        $response = response()->json([ 'data' => 'Content not found' ], 404);

        if ($content) {
            $data = [];

            foreach ($connections as $connection) {
                $data []= $this->publish($content, $connection);
            }

            $response = response()->json([ 'data' => 'Content published' ], 201);
        }

        return $response;
    }

	public function publish($content, $connection = null)  {
		// - this will need to be dynamic ( database provider table? )
		// -- Once we hook up another API i will know how i should organize this
        if (!$connection) {
            $connection = $content->connection;
        }

		$class = 'Connections\API\\'.$connection->provider->class_name;
		$create = (new $class($content, $connection))->createPost();

		$content->published = 1;
		$content->ready_published = 0;
		$content->written = 0;
		$content->save();
	}

    public function publishAndRedirect(Content $content)
    {
        $this->publish($content);

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

        $action = $request->input('action');

        $content->ready_published = $action == 'ready_to_publish' ? 1 : 0;
        $content->written = $action == 'written_content' ? 1 : 0;
        $content->published = $action == 'publish' ? 1 : 0;

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
		$conType = ContentType::find($contentType);
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

        $dueDate = new Carbon($content->due_date);

		// - Lets get out of here
		return redirect()->route('contentIndex')->with([
		    'flash_message' => 'You have created content titled '.$content->title.'.',
		    'flash_message_type' => 'success',
		    'flash_message_important' => true
		]);
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
