<?php

namespace App\Http\Controllers;

use App\Campaign;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CampaignCollaboratorsController extends Controller
{
    public function index(Request $request, Campaign $campaign)
    {
        $possibleCollaborators = $campaign->account->users;
        $currentCollaborators = $campaign->collaborators->keyBy('id');
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
}
