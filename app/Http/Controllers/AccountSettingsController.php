<?php

namespace App\Http\Controllers;

use App\Account;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountSettingsController extends Controller
{
    public function index(Request $request)
    {
        $data = [
            'user' => Auth::user(),
            'account' => Account::selectedAccount(),
        ];

        return view('settings.account', $data);
    }

    public function showSubscription(){
        $data = [
            'user' => Auth::user(),
            'account' => Account::selectedAccount(),
        ];

        return view('settings.subscription', $data);
    }
}
