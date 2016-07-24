<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model {

    public function contents()
    {
       return $this->hasMany('App\Content');
    }
}