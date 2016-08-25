<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Idea;

use App\IdeaContent;

use Auth;

class IdeaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        $idea->save();

        $idea_contents = array();

        if(!empty($contents) && is_array($contents)){
            foreach($contents as $content){
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

        //do sanity/success checks here
        echo json_encode([$idea->name,$idea->text,$idea->tags,$idea_contents]);
        exit;
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
