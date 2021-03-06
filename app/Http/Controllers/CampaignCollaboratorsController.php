<?php

namespace App\Http\Controllers;

use App\Account;
use App\Campaign;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CampaignCollaboratorsController extends Controller
{
    /**
     * Listing of campaign collaborators.
     *
     * @param Request $request
     * @param Campaign $campaign
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, Campaign $campaign)
    {
        $possibleCollaborators = $campaign->account->users;
        $currentCollaborators = $campaign->collaborators->keyBy('id');
        $showPossibleCollaborators = $request->input('possible_collaborators') === '1';

        $collaborators = $this->intersectCollaborators($possibleCollaborators, $currentCollaborators);

        if (!$showPossibleCollaborators) {
            $collaborators = $this->onlyCollaborators($collaborators);
        }

        return response()->json([ 'data' => $collaborators ]);
    }

    public function accountCollaborators(Request $request)
    {
        $possibleCollaborators = Account::selectedAccount()->users;
        $collaborators = $this->intersectCollaborators($possibleCollaborators, collect([]));

        return response()->json([ 'data' => $collaborators ]);
    }

    protected function onlyCollaborators($collaborators)
    {
        return $collaborators->filter(function($user) {
            return $user->is_collaborator;
        })->values();
    }

    protected function intersectCollaborators($possibleCollaborators, $currentCollaborators)
    {
        return $possibleCollaborators->map(function($user) use ($currentCollaborators) {
            $user->is_collaborator = $currentCollaborators->has($user->id);
            $user->is_logged_user = $user->id === Auth::id();
            $user->profile_image = $user->present()->profile_image;
            $user->location = $user->present()->location;

            return $user;
        });
    }

    /**
     * Updates campaign collaborators.
     *
     * @param Request $request
     * @param Campaign $campaign
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Campaign $campaign)
    {
        $campaign->collaborators()->sync($request->input('authors'));

        return response()->json(['data' => 'ok']);
    }
}
