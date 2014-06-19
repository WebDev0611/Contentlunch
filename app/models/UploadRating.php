<?php

use LaravelBook\Ardent\Ardent;

class UploadRating extends Ardent {

  protected $table = 'upload_ratings';

  public $autoHydrateEntityFromInput = true;

  public $forceEntityHydrationFromInput = true;

  protected $fillable = [
    'upload_id', 'user_id', 'rating'
  ];

  public static $rules = [
    'upload_id' => 'required|unique:upload_ratings,upload_id,id,user_id,id,null',
    'user_id' => 'required',
    'rating' => 'required'
  ];

}
