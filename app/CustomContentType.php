<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomContentType extends Model
{
    protected $fillable = [
      'name'
    ];

    public function contents()
    {
        return $this->hasMany('App\Content');
    }
}
