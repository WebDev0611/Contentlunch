<?php namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;

class Content extends Model {

    public static function boot()
    {
        parent::boot();

        static::updating( function($content){
            $content->logChanges();
        });
    }

    public function logChanges($userId = null) 
    {
            $userId = $userId ?: Auth::id();
            $changed  = $this->getDirty();
            $this->adjustments()->attach($userId, [
                'before'  => json_encode(array_intersect_key($this->fresh()->toArray(), $changed)),
                'after'     => json_encode($changed)
              ]);

    }

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

    public function adjustments() 
    {
       return $this->belongsToMany('App\User', 'adjustments')
                          ->withTimestamps()
                          ->withPivot(['before', 'after'])
                          ->latest('pivot_updated_at');
    }

    // - Eek not sure if this make sense to pull user specific drop down from contents model
    // -- maybe from user model with different function name
    public static function dropdown($user = null)
    {
        $user = $user ?: Auth::user();
          // - Create Related Drop Down Data
          $relateddd = ['' => '-- Select Related Content --'];
          $relateddd = $user->contents()->select('id','title')->orderBy('title', 'asc')->distinct()->lists('title', 'id')->toArray();
          return $relateddd;
    }

}