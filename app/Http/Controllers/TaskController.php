<?php

namespace App\Http\Controllers;

use App\Account;
use App\Attachment;
use App\Calendar;
use App\Campaign;
use App\Content;
use App\Helpers;
use App\Task;
use App\Transformers\TaskTransformer;
use Auth;
use Fractal;
use Illuminate\Http\Request;
use Storage;
use View;

class TaskController extends Controller
{
    protected $selectedAccount;

    public function __construct()
    {
        $this->selectedAccount = Account::selectedAccount();
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $shouldReturnAccountTasks = $request->account_tasks == '1';

        return $shouldReturnAccountTasks
            ? $this->paginatedAccountTasks()
            : $this->paginatedUserTasks();
    }

    protected function paginatedAccountTasks()
    {
        $tasks = $this->paginatedAccountTasksQuery()->paginate(6);

        Fractal::getManager()->parseIncludes([ 'user', 'contents.status' ]);

        return Fractal::collection($tasks, new TaskTransformer, function($resources) {
            $resources->setMetaValue('total', $this->paginatedAccountTasksQuery()->count());
        });
    }

    protected function paginatedAccountTasksQuery()
    {
        return $this->selectedAccount
            ->tasks()
            ->with('user')
            ->with('contents.status')
            ->orderBy('created_at', 'desc')
            ->open();
    }

    protected function paginatedUserTasks()
    {
        $tasks = $this->paginatedUsersTasksQuery()->paginate(6);

        Fractal::getManager()->parseIncludes([ 'user', 'contents.status' ]);

        return Fractal::collection($tasks, new TaskTransformer, function($resources) {
            $resources->setMetaValue('total', $this->paginatedUsersTasksQuery()->count());
        });
    }

    protected function paginatedUsersTasksQuery()
    {
        return Auth::user()
            ->assignedTasks()
            ->where('account_id', $this->selectedAccount->id)
            ->with('user')
            ->with('contents.status')
            ->orderBy('tasks.created_at', 'desc')
            ->open()
            ->distinct();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $task = $this->createTaskFromRequest($request);

        $this->saveAttachments($request, $task);
        $this->saveAsContentTask($request, $task);
        $this->saveAsCampaignTask($request, $task);
        $this->saveAsCalendarTask($request, $task);

        if (!$assignedUsers = $request->input('assigned_users')) {
            $assignedUsers = [ Auth::id() ];
        }

        $task->assignUsers($assignedUsers);

        return $this->taskResponse($task);
    }

    protected function taskResponse($task)
    {
        $task = Task::with('user')->find($task->id);

        $task->due_date_diff = $task->present()->dueDate();
        $task->user->profile_image = $task->user->present()->profile_image;

        return response()->json($task);
    }

    protected function createTaskFromRequest(Request $request)
    {
        return Task::create([
            'name' => $request->input('name'),
            'url' => $request->input('url'),
            'explanation' => $request->input('explanation'),
            'start_date' => $request->input('start_date'),
            'due_date' => $request->input('due_date'),
            'user_id' => Auth::id(),
            'account_id' => Account::selectedAccount()->id,
            'status' => 'open',
        ]);
    }

    private function saveAttachments(Request $request, Task $task)
    {
        $fileUrls = $request->input('attachments');
        $userId = Auth::id();
        $userFolder = "attachment/$userId/tasks/";

        if (!empty($fileUrls)) {
            foreach ($fileUrls as $fileUrl) {
                $movedS3Path = $this->moveFileToUserFolder($fileUrl, $userFolder);
                $attachment = $this->createAttachment($movedS3Path);
                $task->attachments()->save($attachment);
            }
        }
    }

    private function createAttachment($movedS3Path)
    {
        return Attachment::create([
            'filePath' => $movedS3Path,
            'filename' => Storage::url($movedS3Path),
            'type' => 'file',
            'extension' => Helpers::extensionFromS3Path($movedS3Path),
            'mime' => Storage::mimeType($movedS3Path)
        ]);
    }

    private function moveFileToUserFolder($fileUrl, $userFolder)
    {
        $fileName = substr(strstr($fileUrl, '_tmp/'), 5);
        $newPath = $userFolder . $fileName;
        $s3Path = Helpers::s3Path($fileUrl);
        Storage::move($s3Path, $newPath);

        return $newPath;
    }

    protected function saveAsContentTask(Request $request, Task $task)
    {
        $contentId = $request->input('content_id');

        if ($contentId && Content::find($contentId)->count()) {
            $task->contents()->attach($contentId);
        }
    }

    protected function saveAsCampaignTask(Request $request, Task $task)
    {
        $campaignId = $request->input('campaign_id');

        if ($campaignId && Campaign::find($campaignId)->count()) {
            $task->campaigns()->attach($campaignId);
        }
    }

    protected function saveAsCalendarTask(Request $request, Task $task)
    {
        $calendarId = $request->input('calendar_id');
        $calendar = Calendar::find($calendarId);

        if ($calendarId && $calendar->count()) {
            $task->calendar()->associate($calendar);
            $task->save();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Task $task
     * @return View
     * @internal param int $id
     */
    public function edit(Task $task)
    {
        if (Auth::user()->cant('show', $task)) {
            abort(404);
        }

        $data = [
            'task' => $task,
            'assignableUsers' => Account::selectedAccount()->getUsers(),
        ];

        return view('task.index', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Task $task
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function update(Request $request, Task $task)
    {
        $task->update([
            'name' => $request->input('name'),
            'url' => $request->input('url'),
            'explanation' => $request->input('explanation'),
            'start_date' => $request->input('start_date'),
            'due_date' => $request->input('due_date'),
        ]);

        $task->assignUsers($request->input('assigned_users', []));

        $this->saveAttachments($request, $task);

        return response()->json(['success' => true, 'task' => $task ]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param Task $task
     * @return \Illuminate\Http\Response
     * @internal param Request $request
     * @internal param int $id
     */
    public function close(Task $task)
    {
        if (Auth::user()->cant('update', $task)) {
            return response()->json([ 'success' => false ], 403);
        }

        $task->close();

        return response()->json(['success' => true, 'task' => $task ]);;
    }

    public function open(Task $task)
    {
        if (Auth::user()->cant('update', $task)) {
            return response()->json([ 'success' => false ], 403);
        }

        $task->open();

        return response()->json(['success' => true, 'task' => $task ]);
    }

    protected function loggedUserCanClose(Task $task)
    {
        return $task->users->filter(function($user) {
            return $user->id == Auth::id();
        });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Task $task
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function destroy(Task $task)
    {
        $response = response()->json([ 'data' => 'Permission denied' ], 403);

        if (Auth::user()->can('destroy', $task)) {
            $task->delete();
            $response = response()->json([ 'data' => 'Resource deleted' ], 201);
        }

        return $response;
    }
}
