<?php

use LaravelBook\Ardent\Ardent;

class ForumThread extends Ardent {

    protected $table = 'forum_threads';

    public $autoHydrateEntityFromInput    = true;
    public $forceEntityHydrationFromInput = true;

    protected $fillable = [
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
        return $this->hasMany('ForumThreadReply')->with('user');
    }

    public function reply_count()
    {
        return $this->hasOne('ForumThreadReply')->selectRaw('forum_thread_id, count(*) as reply_count')->groupBy('forum_thread_id');
    }


    public function user()
    {
        return $this->belongsTo('User')->with('image');
    }

    protected function beforeSave()
    {
        if (is_array(@$this->tags)) {
            $this->tags = json_encode($this->tags);
        }
    }

    public function toArray()
    {
        $values = parent::toArray();
        if (is_string(@$values['tags'])) {
            $values['tags'] = @json_decode($values, true);
        }
        if (!@$values['tags']) {
            $values['tags'] = [];
        }

        if (!@$values['reply_count']) {
            if (@$values['replies']) {
                $values['reply_count'] = count($values['replies']);
            } else {
                $values['reply_count'] = 0;
            }
            
        } else {
            $values['reply_count'] = @$values['reply_count']['reply_count'] ?: 0;
        }

        return $values;
    }

}
