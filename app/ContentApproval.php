<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContentApproval extends Model
{
    public $fillable = [ 'content_id', 'user_id' ];
    public $table = 'content_approvals';
}
