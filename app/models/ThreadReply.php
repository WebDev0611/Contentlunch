<?php

use LaravelBook\Ardent\Ardent;

class ForumThreadReply extends Ardent {

    protected $table = 'thread_replies';

    public $autoHydrateEntityFromInput    = true;
    public $forceEntityHydrationFromInput = true;

    protected $fillable = [
        'forum_thread_id',
        'user_id',
        'body',
    ];

    public static $rules = [
        'forum_thread_id' => 'required',
        'user_id'         => 'required',
        'body'            => 'required',
    ];

    public function thread()
    {
        return $this->hasOne('Thread');
    }

    public function user()
    {
        return $this->hasOne('User')->with('image');
    }

}
