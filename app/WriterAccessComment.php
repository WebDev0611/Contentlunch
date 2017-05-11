<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class WriterAccessComment extends Model {

    public function user ()
    {
        return $this->belongsTo('App\User');
    }
}
