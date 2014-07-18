<?php

use LaravelBook\Ardent\Ardent;

class Announcement extends Ardent {

    public $autoHydrateEntityFromInput    = true;
    public $forceEntityHydrationFromInput = true;

    protected $fillable = ['message'];

    public static $rules = [
        'message' => 'required'
    ];

}