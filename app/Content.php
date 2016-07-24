<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Content extends Model {

    public function authors()
    {
       return $this->belongsToMany('App\User');
    }
    // tag linking
    public function tags()
    {
       return $this->belongsToMany('App\Tag');
    }  
    // - holds images and files
    public function attachments()
    {
       return $this->hasMany('App\Attachment');
    }  
    // campaign 
    public function campaign()
    {
       return $this->belongsTo('App\Campaign');
    }
    // - related content
    public function related()
    {
       return $this->belongsToMany('App\Content', 'content_related', 'content_id', 'related_content_id');
    }
}