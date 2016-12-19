<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

use App\Http\Requests;
use App\Content;

class ContentCollaboratorsController extends Controller
{
    public function index(Request $request, Content $content)
    {
        $possibleCollaborators = $content->account->users;
        $currentCollaborators = $content->authors->keyBy('id');
        $showCurrentCollaboratorsOnly = $request->input('current_collaborators') === '1';

        $collaborators = $possibleCollaborators->map(function($user) use ($currentCollaborators) {
            $user->is_collaborator = $currentCollaborators->has($user->id);
            $user->is_logged_user = $user->id === Auth::user()->id;

            return $user;
        });

        if ($showCurrentCollaboratorsOnly) {
            $collaborators = $collaborators->filter(function($user) {
                return $user->is_collaborator;
            })->values();
        }

        return response()->json([ 'data' => $collaborators ]);
    }

    public function update(Request $request, Content $content)
    {
        $content->authors()->detach();
        $content->authors()->attach($request->input('authors'));

        return response()->json([
            'data' => Content::find($content->id)->authors()->get()
        ]);
    }
}
