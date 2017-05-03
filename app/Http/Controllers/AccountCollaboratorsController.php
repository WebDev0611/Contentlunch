<?php

namespace App\Http\Controllers;

use App\Account;
use App\Services\AccountService;
use Auth;
use Illuminate\Http\Request;

class AccountCollaboratorsController extends Controller
{
    protected $request;
    protected $selectedAccount;
    protected $accountService;

    public function __construct(Request $request, AccountService $accountService)
    {
        $this->request = $request;
        $this->accountService = $accountService;
        $this->selectedAccount = Account::selectedAccount();
    }

    public function index()
    {
        return response()->json($this->accountService->formattedCollaborators());
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
