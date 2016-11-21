<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;

use Campaign;
use User;
use Auth;
use View;

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
        $my_campaigns = Auth::user()->campaigns()->get();
        $my_tasks = Auth::user()->tasks()->get();
        return View::make('home.list',[
            'mycampaigns' => $my_campaigns->toJson(),
            'tasks' => $my_tasks->toJson()
        ]);
    }
}
