<?php

namespace App\Http\Controllers;

use App\Account;
use App\Http\Requests;
use Illuminate\Http\Request;

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

                return $user;
            });

        return response()->json([ 'data' => $users ]);
    }
}
