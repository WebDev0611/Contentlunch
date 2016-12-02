<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Task;
use App\Helpers;
use App\Attachment;
use Auth;
use Storage;
use View;
use App\Account;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $task = Task::create([
            'name' => $request->input('name'),
            'explanation' => $request->input('explanation'),
            'start_date' => $request->input('start_date'),
            'due_date' => $request->input('due_date'),
            'user_id' => Auth::id(),
            'account_id' => Account::selectedAccount()->id,
            'status' => 'open',
        ]);

        $this->saveAttachments($request, $task);

        return response()->json($task);
    }

    private function saveAttachments($request, $task)
    {
        $fileUrls = $request->input('attachments');
        $userId = Auth::id();
        $userFolder = "/attachment/$userId/tasks/";
        if(!empty($fileUrls)){
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $task = Task::where(['id'=> $id, 'user_id' => Auth::id() ])->first();
        return View::make('task/index',['task'=>$task]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {

        //
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

        $task = Task::where(['id'=> $id, 'user_id' => Auth::id() ])->first();

        $task->name = $request->input('name');
        $task->explanation = $request->input('explanation');
        $task->start_date = $request->input('start_date');
        $task->due_date = $request->input('due_date');

        $task->save();

        return response()->json(['success' => true, 'task' => $task ]);
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function close(Request $request, $id)
    {

        $task = Task::where(['id'=> $id, 'user_id' => Auth::id() ])->first();
        $task->status = 'closed';
        $task->save();

        return response()->json(['success' => true, 'task' => $task ]);
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
