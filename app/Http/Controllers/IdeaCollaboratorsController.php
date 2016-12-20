<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Idea;

class IdeaCollaboratorsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Idea $idea)
    {
        $possibleCollaborators = $idea->account->users;
        $currentCollaborators = $idea->collaborators->keyBy('id');
        $showPossibleCollaborators = $request->input('possible_collaborators') === '1';

        $collaborators = $possibleCollaborators->map(function($user) use ($currentCollaborators) {
            $user->is_collaborator = $currentCollaborators->has($user->id);
            $user->is_logged_user = $user->id === Auth::user()->id;

            return $user;
        });

        if (!$showPossibleCollaborators) {
            $collaborators = $collaborators->filter(function($user) {
                return $user->is_collaborator;
            })->values();
        }

        return response()->json([ 'data' => $collaborators ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Idea $idea
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Idea $idea)
    {
        $idea->collaborators()->detach();
        $idea->collaborators()->attach($request->input('collaborators'));

        return response()->json([
            'data' => Idea::find($idea->id)->collaborators()->get()
        ]);
    }
}
