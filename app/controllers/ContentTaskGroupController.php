<?php

class ContentTaskGroupController extends BaseController {

    public function index($accountId, $contentId)
    {
        // TODO: permissions

        return ContentTaskGroup::where('content_id', $contentId)
            ->with('tasks')
            ->get();
    }

    public function update($accountId, $contentId)
    {
        // TODO: permissions

        $input = Input::all();
        $id = @$input['id'];

        $taskGroup = ContentTaskGroup::find($id);
        
        // handling no TaskGroup with that ID (or no ID)
        if (is_null($taskGroup)) {
            return $this->responseError("No Content Task Group with the ID '{$id}'");
        }

        // fill out or $taskGroup with input (guards against tasks)
        $taskGroup->fill($input);
        $taskGroup->content_id = $contentId;

        if (!$taskGroup->save()) {
            return $this->responseError($taskGroup->errors()->all(':message'));
        }

        $errors = [];
        foreach ($input['tasks'] as $index => $t) {
            if (@$t['id']) $task = ContentTask::find($t['id']);
            else $task = new ContentTask();
            $task->fill($t);
            $success = $taskGroup->tasks()->save($task);

            // try to save what we can... but still mark errors
            if (!$success) {
                foreach ($task->errors()->all(':message') as $error) {
                    $i = $index + 1;
                    $errors[] = "Task [$i] - $error";
                }
            }
        }

        if (!empty($errors)) {
            return $this->responseError($errors);
        }

        return ContentTaskGroup::with('tasks')->find($taskGroup->id);
    }

}