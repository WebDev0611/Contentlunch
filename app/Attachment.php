<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    public $fillable = [ 'filepath', 'filename', 'type', 'extension', 'mime' ];
    public $appends = [ 'name' ];

    public function contents()
    {
        return $this->hasOne('App\Content');
    }

    public function getNameAttribute()
    {
        $pattern = "/attachment\/(\d+\/(files|images)|_tmp)\//";

        return preg_replace($pattern, '', $this->filepath);
    }
}
