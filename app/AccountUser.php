<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccountUser extends Model
{
    public $fillable = [ 'account_id', 'user_id' ];
    public $table = 'account_user';
}
