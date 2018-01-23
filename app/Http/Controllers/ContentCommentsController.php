<?php

namespace App\Http\Controllers;

use App\Content;
use App\ContentApproval;
use App\ContentComment;
use App\ContentGuest;
use App\ContentType;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
     * List of commenting users for a piece of content.
     *
     * @param Content $content
     * @return \Illuminate\Http\Response
     */
    public function contentReviewData(Request $request, Content $content)
    {
        $reviewers = User::join('content_comments', 'content_comments.user_id', '=', 'users.id')
            ->join('contents', 'contents.id', '=', 'content_comments.content_id')
            ->select(DB::RAW('DISTINCT(users.id), users.name, users.email, users.profile_image'))
            ->with(['comments' => function($q) use ($content){ $q->where('content_id', $content->id); }])
            ->where("content_id", "=", $content->id)
            ->get();

        $invitees = ContentGuest::join("users", "users.id", "=", "content_guest.user_id")
            ->select("users.id", "users.name", "users.email", "users.profile_image")
            ->where("content_id","=",$content->id)
            ->get();

        $approvers = ContentApproval::join("users", "users.id", "=", "content_approvals.user_id")
            ->select("users.id", "users.name", "users.email", "users.profile_image")
            ->where("content_id","=",$content->id)
            ->get();

        $author = User::join("contents", "contents.user_id", "=", "users.id")
            ->select("users.id", "users.name", "users.email", "users.profile_image")
            ->where("contents.id","=",$content->id)
            ->get();

        $contentType = ContentType::query()
            ->where("id", "=", $content->content_type_id)
            ->get();

        $reviewData = (object) array(
            "id" => $content->id,
            "content_type" => $contentType[0]->name,
            "title" => $content->title,
            "due_date" => $content->due_date,
            "author" => $author[0],
            "invitees" => $invitees,
            "approvers" => $approvers,
            "reviewers" => $reviewers,
        );

        return response()->json($reviewData);
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
