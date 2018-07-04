<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    public $fillable = [ 'filepath', 'filename', 'type', 'extension', 'mime' ];
    public $appends = [ 'name' ];

    public function campaigns()
    {
        return $this->belongsToMany('App\Campaign')->withTimestamps();
    }

    public function contents()
    {
        return $this->hasOne('App\Content');
    }

    public function task()
    {
        return $this->hasOne('App\Task');
    }

    public function getNameAttribute()
    {
        $pattern = "/attachment\/(\d+\/(files|images)|_tmp)\//";

        return preg_replace($pattern, '', $this->filepath);
    }
}
