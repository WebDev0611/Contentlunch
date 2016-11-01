<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Task;
use Auth;
use Storage;
use Helpers;

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
        $task = new Task;

        $task->name = $request->input('name');
        $task->explanation = $request->input('explanation');
        $task->start_date = $request->input('start_date');
        $task->due_date = $request->input('due_date');
        $task->user_id = Auth::id();
        $task->account_id = Auth::user()->account->id;
        $task->status = 'open';
        $task->save();

        $this->saveAttachments($request, $task->id);

        echo json_encode( $task );
    }

    private function saveAttachments($request, $taskId)
    {
        $fileUrls = $request->input('attachments');
        $userId = Auth::id();
        $userFolder = "/attachments/$userId/tasks/";

        foreach ($fileUrls as $fileUrl) {
            $movedUrl = $this->moveFileToUserFolder($fileUrl, $userFolder);
        }
        dd($fileUrls);
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
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
