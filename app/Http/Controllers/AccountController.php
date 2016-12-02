<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

use App\Account;

class AccountController extends Controller
{
    public function stats()
    {
        $my_campaigns = Auth::user()->campaigns()->get();
        $my_tasks = Auth::user()->tasks->get();

        return view('home.index', [
            'mycampaigns' => $my_campaigns->toJson(),
        ]);
    }

    public function selectAccount(Request $request, Account $account)
    {
        Account::selectAccount($account);

        return response()->json([ 'account' => $account->id ]);
    }
}
