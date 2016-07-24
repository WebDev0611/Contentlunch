<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class ContentType extends Model {

    protected $hidden = [ 'content_type_id', 'connection_id', 'created_at', 'updated_at', 'user_id', 'campaign_id'];
    protected $fillable = ['title', 'body', 'buying_stage', 'user_id', 'settings'];


    public function contents()
    {
       return $this->hasMany('App\Content');
    }
    
}