<?php

namespace App\Http\Controllers;

use App\Campaign;
use App\Task;
use Illuminate\Http\Request;

class CampaignTasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Campaign $campaign)
    {
        $openTasks = $request->input('open') == '1';
        $tasks = Task::resourceTasks($campaign, $openTasks);

        return response()->json($tasks);
    }
}
