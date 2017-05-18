<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContentMessage extends Model
{
    public $table = 'content_messages';
    public $fillable = [
        'sender_id', 'content_id', 'body',
    ];

    public function content()
    {
        return $this->belongsTo('App\Content');
    }
}
