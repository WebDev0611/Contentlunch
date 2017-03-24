<?php

namespace App\Http\Controllers;

use App\Account;
use Illuminate\Http\Request;
use Auth;

class AccountCollaboratorsController extends Controller
{
    protected $request;
    protected $selectedAccount;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->selectedAccount = Account::selectedAccount();
    }

    public function index()
    {
        $account = $this->selectedAccount->parentAccount ?: $this->selectedAccount;

        $users = $account
            ->users()
            ->get()
            ->map(function($user) {
                $user->profile_image = $user->present()->profile_image;
                $user->location = $user->present()->location;
                $user->total_tasks = $user->tasks()->where('status', 'open')->count();

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
