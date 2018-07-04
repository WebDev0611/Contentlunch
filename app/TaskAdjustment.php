<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskAdjustment extends Model
{
    protected $table = 'task_adjustments';

    protected $fillable = [
        'task_id',
        'user_id',
        'before',
        'after',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function task()
    {
        return $this->belongsTo('App\Task');
    }

    public function hasKey($keyName)
    {
        $changes = collect(json_decode($this->after));

        return $changes->has($keyName);
    }

    public function statusChangeDescription()
    {
        $changes = json_decode($this->after);
        $statusDescription = null;

        if (collect($changes)->has('status')) {
            $statusVerb = $this->statusVerb($changes->status);
            $taskName = $this->task->name;

            if ($this->user) {
                $userName = $this->user->name;
                $statusDescription = "$userName $statusVerb the task '$taskName'.";
            } else {
                $statusDescription = "The task '$taskName' was $statusVerb.";
            }
        }

        return $statusDescription;
    }

    protected function statusVerb($status)
    {
        switch ($status) {
            case 'open': return 'opened';
            default: return $status;
        }
    }
}
