<?php

use LaravelBook\Ardent\Ardent;

class ContentTag extends Ardent {

  protected $table = 'content_tags';

  public $autoHydrateEntityFromInput = true;

  public $forceEntityHydrationFromInput = true;

  protected $fillable = [
    'content_id', 'tag'
  ];

  public static $rules = [
    'content_id' => 'required',
    'tag' => 'required'
  ];

}
