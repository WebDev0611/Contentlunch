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
        $this->selectedAccount = Account::selectedAccount();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tasks = $this->loggedUserTasks()->map(function($task) {
                return $this->addDueDateDiffs($task);
            })->toJson();

        $accountTasks = $this->accountTasks()->map(function($task) {
                return $this->addDueDateDiffs($task);
            })->toJson();

        $mycampaigns = $this->myCampaigns()->toJson();

        return view('home.list', compact('mycampaigns', 'tasks', 'accountTasks'));
    }

    protected function addDueDateDiffs(Task $task)
    {
        $task->due_date_diff = $task->present()->dueDate;
        $task->updated_at_diff = $task->present()->updatedAt;
        $task->created_at_diff = $task->present()->createdAt;

        return $task;
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
