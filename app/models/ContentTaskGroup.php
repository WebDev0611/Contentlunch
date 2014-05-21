<?php

use LaravelBook\Ardent\Ardent;

class ContentTaskGroup extends Ardent {

    public $autoHydrateEntityFromInput = false;
    public $forceEntityHydrationFromInput = false;

    protected $guarded = [
        'id',
        'status',
        'content_id',
        'created_at',
        'updated_at',
        'tasks',
    ];

    public static $rules = [
        'content_id' => 'required',
        'status'     => 'required',
        'due_date'   => 'required',
    ];

    public function content()
    {
        return $this->belongsTo('Content');
    }

    public function tasks()
    {
        return $this->hasMany('ContentTask');
    }

}
