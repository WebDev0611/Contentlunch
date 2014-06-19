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
        return $this->hasMany('ForumThreadReply')->with('user')->with('account');
    }

    public function reply_count()
    {
        return $this->hasOne('ForumThreadReply')->selectRaw('forum_thread_id, count(*) as reply_count')->groupBy('forum_thread_id');
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
        ])->with('image');
    }

    public function account()
    {
        return $this->belongsTo('Account')->select([
            'id',
            'name',
        ]);
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
