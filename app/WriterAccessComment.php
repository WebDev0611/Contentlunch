<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class WriterAccessComment extends Model {

    protected $casts = [
        'from_client' => 'boolean',
        'client_notified' => 'boolean'
    ];

    public function user ()
    {
        return $this->belongsTo('App\User');
    }

    public function scopeUserNotNotified($query)
    {
        return $query->where('client_notified', false);
    }

    public function scopeNotFromClient($query)
    {
        return $query->where('from_client', false);
    }
}
