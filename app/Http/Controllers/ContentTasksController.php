<?php

namespace App\Http\Controllers;

use App\Task;
use Illuminate\Http\Request;
use App\Content;

class ContentTasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Content $content)
    {
        $openTasks = $request->input('open') == '1';
        $tasks = Task::resourceTasks($content, $openTasks);

        return response()->json($tasks);
    }
}
