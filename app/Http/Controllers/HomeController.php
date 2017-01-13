<?php

namespace App\Http\Controllers;

use App\Account;
use App\Http\Requests;
use App\Task;
use Auth;
use Campaign;
use Illuminate\Http\Request;
use User;

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
        $tasks = Task::userTasks(Auth::user())->toJson();
        $accountTasks = Task::accountTasks($this->selectedAccount)->toJson();

        $mycampaigns = $this->myCampaigns()->toJson();

        return view('home.list', compact('mycampaigns', 'tasks', 'accountTasks'));
    }

    protected function myCampaigns()
    {
        return Auth::user()->campaigns()->get();
    }
}
