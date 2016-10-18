<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccountInvite extends Model
{
    public $fillable = [ 'email', 'token', 'account_id' ];

    public function account()
    {
        return $this->belongsTo('App\Account');
    }
}
