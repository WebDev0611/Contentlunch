<?php

namespace App\Http\Controllers;

use App\Account;
use Auth;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Idea;
use Illuminate\Support\Facades\DB;
use Mail;

class IdeaCollaboratorsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param Idea $idea
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Idea $idea)
    {
        $collaborators = $idea->id
            ? $this->getCollaborators($request, $idea)
            : $this->getCollaborators($request);

        return response()->json(['data' => $collaborators]);
    }

    protected function getCollaborators(Request $request, Idea $idea = null)
    {
        $possibleCollaborators = $idea ? $idea->account->users : Account::selectedAccount()->users;
        $currentCollaborators = $idea ? $idea->collaborators->keyBy('id') : collect([]);
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
        } else {
            // Exclude the logged user from possible collaborators list
            $collaborators = $collaborators->filter(function($user) {
                return $user->id !== Auth::user()->id;
            })->values();
        }

        return $collaborators;
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
        $authorIds = collect($request->input('collaborators'))->merge(Auth::id())->toArray();
        $idea->collaborators()->sync($authorIds);

        foreach($authorIds as $authorId){
            $author = DB::table('users')->where('id', $authorId)->first();

            $data = [
                'link' => $_SERVER['HTTP_ORIGIN']."/idea/".$idea->id,
                'user' => Auth::user(),
                'idea' => $idea,
            ];

            Mail::send('emails.invite.idea_socialize', $data, function($message) use ($author) {
                $message->from("invites@contentlaunch.com", "Content Launch")
                    ->to($author->email)
                    ->subject('You\'ve been invited to view and idea');
            });
        }

        return response()->json([
            'data' => Idea::find($idea->id)->collaborators()->get()
        ]);
    }
}
