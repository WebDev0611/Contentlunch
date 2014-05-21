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

    public function content()
    {
        return $this->belongsTo('TaskGroup');
    }

    public function assignee()
    {
        return $this->belongsTo('User');
    }
}
