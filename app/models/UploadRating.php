<?php

use LaravelBook\Ardent\Ardent;

class UploadRating extends Ardent {

  protected $table = 'upload_ratings';

  public $autoHydrateEntityFromInput = true;

  public $forceEntityHydrationFromInput = true;

  protected $fillable = [
    'upload_id', 'rating'
  ];

  public static $rules = [
    'upload_id' => 'required',
    'rating' => 'required'
  ];

}
