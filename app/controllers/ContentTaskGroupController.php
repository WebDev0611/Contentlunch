<?php

class ContentTaskGroupController extends BaseController {

    public function getForCalendar($accountID) {
        ini_set('xdebug.var_display_max_depth', 10);
        ini_set('xdebug.var_display_max_children', 256);

        $content = Content::where('account_id', $accountID)->lists('id');
        $task_groups = ContentTaskGroup::whereIn('content_id', $content)->lists('id');

        $tasks = ContentTask::with('task_group.content.content_type', 'task_group.content.campaign', 'task_group.content.user', 'user')
            ->whereIn('content_task_group_id', $task_groups);


        if(Input::get('start')) {
            $tasks->where('due_date', '>=', Input::get('start'));
        }
        if(Input::get('end')) {
            $tasks->where('due_date', '<=', Input::get('end'));
        }

        return $tasks->get();
    }
    
    // returns a list of ALL tasks (not task groups) regardless of content
    // (this is used in the calendar)
    public function getAllTasks($accountID) 
    {
        $contentTasks = ContentTask::join('content_task_groups as ctg', 'ctg.id', '=', 'content_task_group_id')
            ->join('content', 'ctg.content_id', '=', 'content.id')
            ->with('user')
            ->where('content.account_id', $accountID);

        $contentTasks = $contentTasks->get()->toArray();

        return $contentTasks;
    }

    public function index($accountID, $contentID)
    {
        // TODO: permissions

        return ContentTaskGroup::where('content_id', $contentID)
            ->with('tasks')
            ->get();
    }

    public function update($accountID, $contentID)
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
        $taskGroup->content_id = $contentID;

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
        $deleteTaskIDs = [];
        foreach ($taskCheck as $id => $deleteTask) {
            if (!$deleteTask) {
                $deleteTaskIDs[] = $id;
            }
        }
        if (!empty($deleteTaskIDs)) {
            ContentTask::destroy($deleteTaskIDs);
        }

        if (!empty($errors)) {
            return $this->responseError($errors);
        }

        return ContentTaskGroup::with('tasks')->find($taskGroup->id);
    }

}