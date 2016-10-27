<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    public $fillable = [ 'filepath', 'filename', 'type', 'extension', 'mime' ];

    public function contents()
    {
        return $this->hasOne('App\Content');
    }
}
