<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccountType extends Model
{
    public $fillable = [ 'name' ];
    public $table = 'account_types';
}
