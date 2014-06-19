<?php

use LaravelBook\Ardent\Ardent;

class UploadView extends Ardent {

  protected $table = 'upload_views';

  protected $fillable = [
    'upload_id', 'user_id'
  ];

  public static $rules = [
    // Multiple columns unique rule (upload_id, user_id)
    'upload_id' => 'required|unique_with:upload_views,user_id',
    'user_id' => 'required'
  ];

}
