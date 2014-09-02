<?php

use LaravelBook\Ardent\Ardent;

class ContentActivity extends Ardent {
  
  protected $table = 'content_activities';

  public static $rules = [
    'content_id' => 'required',
    'user_id' => 'required',
    'activity' => 'required'
  ];

  protected $fillable = [
    'user_id', 'content_id', 'activity'
  ];

  public function content()
  {
    return $this->belongsTo('Content');
  }

  public function user()
  {
    return $this->belongsTo('User')->with('image');
  }

}