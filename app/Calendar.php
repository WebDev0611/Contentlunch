<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Calendar extends Model
{
    protected $table = 'calendar';



    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function contentTypes()
    {
        return $this->belongsToMany('App\ContentType');
    }
}
