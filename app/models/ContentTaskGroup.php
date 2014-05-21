<?php

use LaravelBook\Ardent\Ardent;

class ContentTaskGroup extends Ardent {

    public $autoHydrateEntityFromInput = true;

    public $forceEntityHydrationFromInput = true;

    protected $fillable = [
        'content_id'
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
