<?php

use LaravelBook\Ardent\Ardent;

class ContentRelated extends Ardent {

  public $table = 'content_related';

  public $autoHydrateEntityFromInput = true;

  public $forceEntityHydrationFromInput = true;

  protected $fillable = [
    'content_id', 'related_content_id'
  ];

  public static $rules = [
    'content_id' => 'required',
    'related_content_id' => 'required'
  ];

}
