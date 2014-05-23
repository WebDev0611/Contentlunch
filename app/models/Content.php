<?php

use LaravelBook\Ardent\Ardent;

class Content extends Ardent {

  protected $table = 'content';

  public $autoHydrateEntityFromInput = true;

  //public $forceEntityHydrationFromInput = true;

  protected $fillable = [
    'title', 'body', 'account_id', 'connection_id',
    'buying_stage', 'persona',
    'secondary_buying_stage', 'secondary_persona', 'status',
    'archived', 'concept'
  ];

  public static $rules = [
    'title' => 'required',
    'account_id' => 'required',
    'content_type_id' => 'required',
    'user_id' => 'required'
  ];

  public function campaign()
  {
    return $this->belongsTo('Campaign');
  }

  public function collaborators()
  {
    return $this->belongsToMany('User', 'content_collaborators', 'content_id', 'user_id')->withTimestamps();
  }

  public function comments()
  {
    return $this->hasMany('ContentComment', 'content_id', 'id')->with('user');
  }

  public function content_type()
  {
    return $this->belongsTo('ContentType');
  }

  /**
   * This is the main file that corresponds to the content type
   */
  public function upload()
  {
    return $this->hasOne('Upload', 'id', 'upload_id');
  }

  /**
   * These are extra files attached to the content
   */
  public function uploads()
  {
    return $this->belongsToMany('Upload', 'content_uploads', 'content_id', 'upload_id')->withTimestamps();
  }

  public function task_groups()
  {
    return $this->hasMany('ContentTaskGroup', 'content_id', 'id')->with('tasks');
  }

  public function account_connections()
  {
    return $this->belongsToMany('AccountConnection', 'content_account_connections', 'content_id', 'account_connection_id')
      ->withTimestamps()
      ->with('connection');
  }

  public function related()
  {
    return $this->belongsToMany('Content', 'content_related', 'content_id', 'related_content_id')->withTimestamps();
  }

  public function tags()
  {
    return $this->hasMany('ContentTag', 'content_id', 'id');
  }

  public function user()
  {
    return $this->belongsTo('User');
  }

  public static function boot()
  {
    parent::boot();

    static::created(function ($content) {
      // every content has 4 (and only 4) task groups (one for each status/step)
      for ($i=1; $i <= 4 ; $i++) { 
        $task_group = new ContentTaskGroup();
        $task_group->status = $i;
        $task_group->due_date = date('Y-m-d', time() + 60 * 60 * 24 * 7 * $i);
        $task_group->content_id = $content->id;
        $content->task_groups()->save($task_group);
      }

      return $content;
    });
  }

}
