<?php

namespace App\Http\Controllers;

use App\Account;
use App\Http\Requests;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountCollaboratorsController extends Controller
{
    public function index(Request $request)
    {
        $users = Account::selectedAccount()
            ->users()
            ->get()
            ->map(function($user) {
                $user->profile_image = $user->present()->profile_image;
                $user->location = $user->present()->location;
                $user->total_tasks = $user->tasks()->count();

                return $user;
            });

        return response()->json($users);
    }

    public function delete(Request $request, $id)
    {
        $removingLoggedUser = $id == Auth::user()->id;
        $userBelongsToAccount = (boolean) Account::selectedAccount()
            ->users()
            ->find($id);

        if ($removingLoggedUser) {
            return response()->json([ 'data' => 'Cannot delete logged user' ], 403);
        }
        else if ($userBelongsToAccount) {
            return $this->removeUserFromAccount($id);
        }
        else {
            return response()->json([ 'data' => 'User not found' ], 404);
        }
    }

    protected function removeUserFromAccount($userId)
    {
        Account::selectedAccount()->users()->detach($userId);

        return response()->json([ 'data' => 'User removed from account.' ], 200);
    }
}
