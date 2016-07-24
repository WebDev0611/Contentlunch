<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model {

    public function contents()
    {
       return $this->hasOne('App\Content');
    }
}