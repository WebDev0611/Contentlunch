<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Idea extends Model
{
    protected $table = 'idea';
    public $fillable = [
        'user_id',
        'account_id',
        'name',
        'text',
        'tags',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function account()
    {
        return $this->belongsTo('App\Account');
    }
}
