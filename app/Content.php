<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Content extends Model {

    public function authors()
    {
       return $this->belongsToMany('App\User');
    }

    public function tags()
    {
       return $this->belongsToMany('App\Tag');
    }  
    
    public function campaign()
    {
       return $this->belongsTo('App\Campaign');
    }
}