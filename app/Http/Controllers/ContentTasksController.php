<?php

namespace App\Http\Controllers;

use App\Http\Requests;
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
        $tasks = $content->tasks();

        if ($openTasks) {
            $tasks = $tasks->where('status', '=', 'open');
        }

        $tasks = $tasks
            ->with('user')
            ->with('assignedUsers')
            ->get()
            ->map(function($task) {
                $task->due_date_diff = $task->present()->dueDate;
                $task->user->profile_image = $task->user->present()->profile_image;

                return $task;
            });

        return response()->json($tasks);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
