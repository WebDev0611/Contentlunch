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

        $taskCheck = [];
        $currentTasks = ContentTask::where('content_task_group_id', $taskGroup->id)->get();
        foreach ($currentTasks as $task) {
            $taskCheck[$task->id] = false;
        }

        $errors = [];
        foreach ($input['tasks'] as $index => $t) {
            if (!empty($t['id'])) {
                $task = ContentTask::find($t['id']);

                // user maybe tried to change ID?
                if (is_null($task)) {
                    $task = new ContentTask();
                    unset($t['id']);
                } else {
                    // protect task from being deleted
                    $taskCheck[$t['id']] = true;
                }
            } else { 
                $task = new ContentTask();
            }

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

        // delete any tasks that existed before and don't exist now
        $deleteTaskIds = [];
        foreach ($taskCheck as $id => $deleteTask) {
            if (!$deleteTask) {
                $deleteTaskIds[] = $id;
            }
        }
        if (!empty($deleteTaskIds)) {
            ContentTask::destroy($deleteTaskIds);
        }

        if (!empty($errors)) {
            return $this->responseError($errors);
        }

        return ContentTaskGroup::with('tasks')->find($taskGroup->id);
    }

}