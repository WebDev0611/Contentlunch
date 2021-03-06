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

    public function writer ()
    {
        return $this->belongsTo('App\WriterAccessWriter', 'writer_id', 'writer_id');
    }

    public function editor ()
    {
        return $this->belongsTo('App\WriterAccessWriter', 'editor_id', 'writer_id');
    }

    public function order ()
    {
        return $this->belongsTo('App\WriterAccessOrder', 'order_id', 'order_id');
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
