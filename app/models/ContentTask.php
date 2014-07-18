<?php

use LaravelBook\Ardent\Ardent;

class ContentTask extends Ardent {

    public $autoHydrateEntityFromInput = false;
    public $forceEntityHydrationFromInput = false;

    protected $guarded = [
        'id',
        'task_group_id',
        'created_at',
        'updated_at',
    ];
    public static $rules = [
        'due_date'              => 'required',
        'name'                  => 'required',
        'user_id'               => 'required',
        'content_task_group_id' => 'required',
    ];

    public function task_group()
    {
        return $this->belongsTo('ContentTaskGroup', 'content_task_group_id');
    }

    public function user()
    {
        return $this->belongsTo('User');
    }

    public static function boot()
    {
      parent::boot();

      static::created(function ($task) {
        // Store an activity log for the content
        $user = Confide::user();
        $assignedUser = User::find($task->user_id);
        // this was breaking seeding?
        if (!$task->content) return true;
        $contentID = $task->content->content_id;
        $activity = new ContentActivity([
          'user_id' => $user->id,
          'content_id' => $contentID,
          'activity' => "Assigned 1 task to ". strtoupper($assignedUser->first_name .' '. $assignedUser->last_name)
        ]);
        $activity->save();
      });
    }
}
