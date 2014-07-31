<?php

use LaravelBook\Ardent\Ardent;

class Activity extends Ardent {

    public $table = 'activity';

    public $autoHydrateEntityFromInput    = false;
    public $forceEntityHydrationFromInput = false;

    protected $fillable = ['content_id', 'user_id', 'activity'];

    public static $rules = [
        'content_id' => 'required',
        'user_id'    => 'required',
        'activity'   => 'required',
    ];

    public function user()
    {
        // cut down on unnecessary fields
        return $this->belongsTo('User')->select([
            'id',
            'first_name',
            'last_name',
            'image',
        ])->with('image');
    }

    public function content()
    {
        // cut down on unnecessary fields
        return $this->belongsTo('Content')->select([
            'id',
            'title',
        ]);
    }
}