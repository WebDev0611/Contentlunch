<?php

use LaravelBook\Ardent\Ardent;

class Conference extends Ardent {

  public $autoHydrateEntityFromInput = true;
  public $forceEntityHydrationFromInput = true;

  protected $table = 'conferences';

  public static $rules = [
    'description' => 'required',
    'status' => 'required',
    'topic' => 'required',
    'consultant' => 'required',
    'user_id' => 'required',
    'account_id' => 'required'
  ];

  protected $fillable = [
    'description', 'topic', 'consultant'
  ];

  public function user()
  {
    return $this->belongsTo('User')->with('image');
  }

}