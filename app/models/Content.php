<?php

use LaravelBook\Ardent\Ardent;

class Content extends Ardent {

  protected $table = 'content';

  public $autoHydrateEntityFromInput = true;

  public $forceEntityHydrationFromInput = true;

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

}
