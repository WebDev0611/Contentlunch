<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Campaign;
use User;
use Auth;
use View;

use App\Account;
use App\Http\Requests;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $selectedAccount = Account::selectedAccount();
        $myCampaigns = Auth::user()->campaigns()->get();
        $myTasks = $selectedAccount->tasks;

        return View::make('home.list',[
            'mycampaigns' => $myCampaigns->toJson(),
            'tasks' => $myTasks->toJson()
        ]);
    }
}
