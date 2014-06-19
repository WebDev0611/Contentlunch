<?php

use LaravelBook\Ardent\Ardent;

class UploadView extends Ardent {

  protected $table = 'upload_views';

  protected $fillable = [
    'upload_id', 'user_id'
  ];

  public static $rules = [
    'upload_id' => 'required|unique:upload_views,upload_id,user_id',
    'user_id' => 'required'
  ];

}
