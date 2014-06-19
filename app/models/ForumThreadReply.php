<?php

use LaravelBook\Ardent\Ardent;

class ForumThreadReply extends Ardent {

    protected $table = 'forum_thread_replies';

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
        return $this->belongsTo('ForumThread');
    }

    public function user()
    {
        // cut down on unnecessary fields
        return $this->belongsTo('User')->select([
            'users.id',
            'email',
            'first_name',
            'last_name',
            'image',
            'title',
        ])->with(['accounts' => function ($query) {
            $query->select('accounts.id', 'name');
        }])->with('image');
    }

}
