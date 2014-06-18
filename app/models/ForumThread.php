<?php

use LaravelBook\Ardent\Ardent;

class ForumThread extends Ardent {

    protected $table = 'forum_threads';

    public $autoHydrateEntityFromInput    = true;
    public $forceEntityHydrationFromInput = true;

    protected $fillable = [
        'account_id',
        'user_id',
        'name',
        'description',
        'tags',
    ];

    public static $rules = [
        'account_id'  => 'required',
        'user_id'     => 'required',
        'name'        => 'required',
        'description' => 'required',
    ];

    public function replies()
    {
        return $this->hasMany('ForumThreadReply');
    }

    public function user()
    {
        return $this->hasOne('User')->with('image');
    }

    protected function beforeSave()
    {
        if (is_array($this->tags)) {
            $this->tags = json_encode($this->tags);
        }
    }

    public function toArray()
    {
        $values = parent::toArray();
        if (is_string($values['tags'])) {
            $values['tags'] = json_decode($values, true);
        }

        return $values;
    }

}
