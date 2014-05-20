<?php

class ContentTaskGroupController extends BaseController {

    public function index($accountId, $contentId)
    {
        // TODO: permissions

        return ContentTaskGroup::where('content_id', $contentId)
            ->with('tasks')
            ->get();
    }

    // public function store($contentId)
    // {
    //     // TODO: permissions

    //     $tasks = new ContentTask;

    //     if (!$tasks->save())
    //         return $this->responseError($tasks->errors()->all(':message'));

    //     return $this->show($tasks->id);
    // }

}