<?php

namespace App\Transformers;

use App\Task;
use League\Fractal\TransformerAbstract;

class TaskTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'user',
        'assignedUsers',
        'contents',
    ];

    public function transform(Task $task)
    {
        return [
            'id' => (int) $task->id,
            'name' => $task->name,
            'explanation' => $task->explanation,
            'start_date' => $task->start_date,
            'due_date' => $task->due_date,
            'user_id' => $task->user_id,
            'account_id' => $task->account_id,
            'url' => $task->url,
            'created_at' => (string) $task->created_at,
            'updated_at' => (string) $task->updated_at,
            'status' => $task->status,
            'due_date_diff' => $task->present()->dueDate,
            'updated_at_diff' => $task->present()->updatedAt,
            'created_at_diff' => $task->present()->createdAt,
        ];
    }

    public function includeUser(Task $task)
    {
        return $this->item($task->user, new UserTransformer);
    }

    public function includeAssignedUsers(Task $task)
    {
        return $this->collection($task->assignedUsers, new UserTransformer);
    }

    public function includeContents(Task $task)
    {
        return $this->collection($task->contents, new ContentTransformer);
    }
}
