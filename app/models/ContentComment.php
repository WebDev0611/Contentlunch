<?php

use LaravelBook\Ardent\Ardent;

class ContentComment extends Ardent {

  protected $table = 'content_comments';

  public $autoHydrateEntityFromInput = true;

  public $forceEntityHydrationFromInput = true;

  protected $fillable = [
    'user_id', 'content_id', 'comment'
  ];

  public static $rules = [
    'user_id' => 'required',
    'content_id' => 'required',
    'comment' => 'required'
  ];

  public function user()
  {
    return $this->belongsTo('User');
  }

  public function guest()
  {
    return $this->belongsTo('GuestCollaborator');
  }

}
