<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ContentGuest extends Model
{
    public $fillable = [ 'content_id', 'user_id' ];
    public $table = 'content_guest';

    public function content(){
        return $this->belongsTo('App\Content');
    }

     public function user(){
        return $this->belongsTo('App\User');
    }
}
