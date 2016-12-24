<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Campaign;
use User;
use Auth;

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
        $this->selectedAccount = Account::selectedAccount();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home.list',[
            'mycampaigns' => $this->myCampaigns()->toJson(),
            'tasks' => $this->loggedUserTasks()->toJson(),
            'accountTasks' => $this->accountTasks()->toJson(),
        ]);
    }

    protected function myCampaigns()
    {
        return Auth::user()->campaigns()->get();
    }

    protected function accountTasks()
    {
        return $this->selectedAccount
            ->tasks()
            ->with('user')
            ->get();
    }

    protected function loggedUserTasks()
    {
        return Auth::user()
            ->assignedTasks()
            ->with('user')
            ->distinct()
            ->get();
    }
}
