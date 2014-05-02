<?php

use LaravelBook\Ardent\Ardent;

class Content extends Ardent {

  protected $table = 'content';

  public function comments()
  {
    return $this->belongsToMany('ContentComment');
  }

  public function related()
  {
    return $this->belongsToMany('ContentRelated');
  }

  public function tags()
  {
    return $this->belongsToMany('ContentTag');
  }

}
