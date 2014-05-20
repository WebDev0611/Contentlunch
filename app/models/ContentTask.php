<?php

use LaravelBook\Ardent\Ardent;

class ContentTask extends Ardent {

    public $autoHydrateEntityFromInput = true;

    public $forceEntityHydrationFromInput = true;

    protected $fillable = [
        'content_id'
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
