<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Content;

class ContentCollaboratorsController extends Controller
{
    public function index(Request $request, Content $content)
    {
        $possibleCollaborators = $content->account->users;
        $currentCollaborators = $content->authors->keyBy('id');

        $collaborators = $possibleCollaborators->map(function($user) use ($currentCollaborators) {
            $user->is_collaborator = $currentCollaborators->has($user->id);

            return $user;
        });

        return response()->json([ 'data' => $collaborators ]);
    }
}
