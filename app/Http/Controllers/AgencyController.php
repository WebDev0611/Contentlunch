<?php

namespace App\Http\Controllers;

use App\Account;
use Auth;

class AgencyController extends Controller
{
    public function index()
    {
        $accounts = collect([ Auth::user()->agencyAccount() ])
            ->merge(Auth::user()->agencyAccount()->childAccounts);

        return view('agency.index', compact('accounts'));
    }
}
