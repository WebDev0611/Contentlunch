<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WriterAccessWriter extends Model
{
    public function orders()
    {
        return $this->hasMany('App\WriterAccessOrder', 'writer_id', 'writer_id');
    }
}
