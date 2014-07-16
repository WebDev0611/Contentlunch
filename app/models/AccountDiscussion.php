<?php

use LaravelBook\Ardent\Ardent;

class AccountDiscussion extends Ardent {
    
    public $autoHydrateEntityFromInput    = true;
    public $forceEntityHydrationFromInput = true;

    protected $fillable = [
        'user_id',
        'account_id',
        'body',
    ];

    public static $rules = [
        'user_id'         => 'required',
        'body'            => 'required',
    ];

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
        // cut down on unnecessary fields
        return $this->belongsTo('Account')->select([
            'id',
            'name',
        ]);
    }
}