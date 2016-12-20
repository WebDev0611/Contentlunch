<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Storage;
use View;
use Auth;

use App\Account;
use App\Http\Requests;
use App\Idea;
use App\IdeaContent;
use App\User;
use App\Tag;
use App\Persona;
use App\Helpers;
use App\Content;

class IdeaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ideas = Account::selectedAccount()
            ->ideas()
            ->with('user')
            ->get()
            ->map(function($idea) {
                $idea->created_diff = $idea->createdAtDiff;
                $idea->updated_diff = $idea->updatedAtDiff;

                return $idea;
            });

        return response()->json($ideas);
    }

    // Parks the idea
    public function park(Request $request){
        $id = $request->input('idea_id');
        $idea = Idea::where([['id',$id],['user_id',Auth::id()]])->first();
        $idea->status = 'parked';
        $idea->save();
        return response()->json($idea);
    }

    public function activate(Request $request){
        $id = $request->input('idea_id');
        $idea = Idea::where([['id',$id],['user_id',Auth::id()]])->first();
        $idea->status = 'active';
        $idea->save();
        return response()->json($idea);

    }

    public function reject(Request $request,$id){
        $idea = Idea::where([['id',$id],['user_id',Auth::id()]])->first();
        $idea->status = 'rejected';
        $idea->save();
        return response()->json($idea);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $name     = $request->input('name');
        $text     = $request->input('idea');
        $tags     = $request->input('tags');
        $contents = $request->input('content');
        $status   = $request->input('status');

        $idea         = new Idea;
        $idea->name   = $name;
        $idea->text   = $text;
        $idea->tags   = $tags;
        $idea->status = $status;

        $idea->user_id = Auth::id();
        $idea->account_id = Account::selectedAccount()->id;
        $idea->save();

        $idea_contents = array();

        if(!empty($contents) && is_array($contents)){
            foreach($contents as $content){
                //if its just keywords
                if(isset($content['keyword'])){
                    $idea_content = new IdeaContent;
                    $idea_content->title = "Trending Topic";
                    $idea_content->body = $content['keyword'];

                    $idea_content->idea_id = $idea->id;
                    $idea_content->user_id = Auth::id();

                    $idea_content->save();
                    $idea_contents[] = $idea_content;

                //if its actual content trends
                }else{
                    $idea_content = new IdeaContent;
                    $idea_content->author = $content['author'];
                    $idea_content->body = $content['body'];
                    $idea_content->fb_shares = $content['fb_shares'];
                    $idea_content->google_shares = $content['google_shares'];
                    $idea_content->image = $content['image'];
                    $idea_content->link = $content['link'];
                    $idea_content->source = $content['source'];
                    $idea_content->title = $content['title'];
                    $idea_content->total_shares = $content['total_shares'];
                    $idea_content->tw_shares = $content['tw_shares'];

                    $idea_content->idea_id = $idea->id;
                    $idea_content->user_id = Auth::id();

                    $idea_content->save();
                    $idea_contents[] = $idea_content;
                }
            }
        }

        //do sanity/success checks here
        return response()->json([$idea->name,$idea->text,$idea->tags,$idea_contents]);
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $idea = Idea::where(['id'=> $id, 'user_id' => Auth::id() ])->first();
        //
        $idea->name     = $request->input('name');
        $idea->text     = $request->input('idea');
        $idea->tags     = $request->input('tags');
        $idea->save();

        return response()->json($idea);
    }

    //converts the idea to a piece of content
    public function ideaconvert(Request $request){
        $idea = Idea::where(['id'=> $id, 'user_id' => Auth::id() ])->first();

        $new_content = new Content;

    }
}
