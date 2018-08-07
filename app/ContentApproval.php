<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ContentApproval extends Model
{
    public $fillable = [ 'content_id', 'user_id' ];
    public $table = 'content_approvals';

    public function content(){
        return $this->belongsTo('App\Content');
    }

     public function user(){
        return $this->belongsTo('App\User');
    }
}
