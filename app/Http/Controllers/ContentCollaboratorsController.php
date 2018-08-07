<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

use App\Http\Requests;
use App\Content;
use Illuminate\Support\Facades\Response;

class ContentCollaboratorsController extends Controller
{
    public function index(Request $request, Content $content)
    {
        $possibleCollaborators = $content->account->proxyToParent()->users;
        $currentCollaborators = $content->authors->keyBy('id');
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

        return response()->json([ 'data' => $collaborators ]);
    }

    public function update(Request $request, Content $content)
    {
        $authorIds = collect($request->input('authors'))->merge(Auth::id())->toArray();
        $content->authors()->sync($authorIds);

        return response()->json([
            'data' => Content::find($content->id)->authors()->get()
        ]);
    }
}
