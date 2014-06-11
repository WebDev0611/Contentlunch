<?php

use LaravelBook\Ardent\Ardent;

class UploadTag extends Ardent {

  protected $table = 'upload_tags';

  public $autoHydrateEntityFromInput = true;

  public $forceEntityHydrationFromInput = true;

  protected $fillable = [
    'upload_id', 'tag'
  ];

  public static $rules = [
    'upload_id' => 'required',
    'tag' => 'required'
  ];

}
