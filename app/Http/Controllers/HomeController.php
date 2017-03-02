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
    protected $selectedAccount;

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->selectedAccount = Account::selectedAccount();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home.list');
    }
}
