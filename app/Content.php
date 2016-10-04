<?php namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    public static function boot()
    {
        parent::boot();
        static::updating(function($content) {
            $content->logChanges();
        });
    }

    public function logChanges($userId = null)
    {
        $userId = $userId ?: Auth::id();
        $changed  = $this->getDirty();
        $fresh = $this->fresh()->toArray();

        // - don't want to track file and images ( i don't think )
        // -- removing the input fields i don't want to track and bloat the history
        array_forget($changed, ['updated_at', 'files', 'images']);
        array_forget($fresh, ['updated_at', 'files', 'images']);

        if (count($changed) > 0) {
            $this->adjustments()->attach($userId, [
                'before' => json_encode(array_intersect_key($fresh, $changed)),
                'after' => json_encode($changed)
            ]);
        }

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


    // connection
    public function connection()
    {
        return $this->belongsTo('App\Connection');
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
                          ->withPivot(['before', 'after','id'])
                          ->latest('pivot_updated_at');
    }

    // - Eek not sure if this make sense to pull user specific drop down from contents model
    // -- maybe from user model with different function name
    public static function dropdown($user = null)
    {
        $user = $user ?: Auth::user();
        // - Create Related Drop Down Data
        $relateddd = ['' => '-- Select Related Content --'];
        $relateddd = $user->contents()
            ->select('id','title')
            ->orderBy('title', 'asc')
            ->distinct()
            ->lists('title', 'id')
            ->toArray();

        return $relateddd;
    }

    /**
     * Configures the flags ready_published, written and published according to the
     * $action string
     */
    public function configureAction($action = null)
    {
        if ($action) {
            $this->ready_published = $action == 'ready_to_publish' ? 1 : 0;
            $this->written = $action == 'written_content' ? 1 : 0;
            $this->published = $action == 'publish' ? 1 : 0;
        }
        else {
            $this->published = 0;
            $this->ready_published = 0;
            $this->written = 1;
        }
    }

}