<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Idea;
use App\IdeaContent;
use Auth;

class PlanController extends Controller
{
    public function index()
    {
        return view('plan.index');
    }

    public function trends()
    {
        return view('plan.trends');
    }

    public function prescription()
    {
        return view('plan.prescription');
    }

    public function editor(Request $request, Idea $idea)
    {
        // $idea = Idea::where(['id' => $id ])->first();

        $idea_content = IdeaContent::where([
            'idea_id' => $idea->id,
            'user_id' => Auth::id()
        ])->get();

        $data = [
            'name' => $idea->name,
            'text' => $idea->text,
            'tags' => $idea->tags,
            'contents' => $idea_content,
            'idea_obj' => $idea,
            'is_collaborator' => $idea->hasCollaborator(Auth::user()),
        ];

        return view('plan.editor', $data);
    }

    public function ideas()
    {
        return view('plan.ideas');
    }

    public function parked()
    {
        return view('plan.parked');
    }
}
