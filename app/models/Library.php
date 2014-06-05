<?php

use LaravelBook\Ardent\Ardent;

class Library extends Ardent {
  
  public $autoHydrateEntityFromInput = true;
  public $forceEntityHydrationFromInput = true;

  public static $rules = [
    'name' => 'required|min:5',
    'user_id' => 'required'
  ];

  protected $table = 'libraries';

  protected $fillable = [
    'name', 'user_id', 'account_id', 'global'
  ];

  public function account()
  {
    return $this->hasOne('Account');
  }

  public function uploads()
  {
    return $this->belongsToMany('Upload', 'library_uploads')->withTimestamps()->with('User');
  }

  public function user()
  {
    return $this->hasOne('User');
  }

}