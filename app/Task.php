<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    public $fillable = [
        'name',
        'explanation',
        'start_date',
        'due_date',
        'user_id',
        'account_id',
        'status',
    ];

    public function attachments()
    {
        return $this->hasMany('App\Attachment');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
