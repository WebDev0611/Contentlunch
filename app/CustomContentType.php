<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomContentType extends Model
{
    public function contents()
    {
        return $this->hasMany('App\Content');
    }
}
