<?php

namespace App\Http\Controllers;

use App\Content;
use App\ContentComment;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ContentCommentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Content $content
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Content $content)
    {
        $comments = $content->comments()->get();
        return response()->json($comments);
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
     * @param  \Illuminate\Http\Request $request
     * @param Content $content
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Content $content)
    {
        $validation = $this->commentValidator($request->all());

        if ($validation->fails()) {
            return response()->json($validation->errors(), 400);
        }

        $data = [
            "content_id" => $content->id,
            "user_id" => Auth::user()->id,
            "text" => $request->input("text")
        ];

        $comment = ContentComment::create($data);

        $comment->save();

        return response()->json($comment);
    }

    private function commentValidator($input)
    {
        return Validator::make($input, [
            'text' => 'required',
        ]);
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
