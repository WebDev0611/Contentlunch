<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContentComment extends Model
{
    public $fillable = [ 'content_id', 'user_id', 'text' ];
    public $table = 'content_comments';

    public function content(){
        return $this->belongsTo('App\Content');
    }

    public function user(){
        return $this->belongsTo('App\User');
    }
}
