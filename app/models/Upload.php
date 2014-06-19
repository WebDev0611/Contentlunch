<?php

use Andrew13\Cabinet\CabinetUpload;
use webignition\InternetMediaType\Parser\TypeParser;

class Upload extends CabinetUpload {

  protected $softDelete = true;

  public function account()
  {
    return $this->belongsTo('Account');
  }

  public function libraries()
  {
    return $this->belongsToMany('Library', 'library_uploads')->withTimestamps();
  }

  public function ratings()
  {
    return $this->hasMany('UploadRating', 'upload_id', 'id');
  }

  public function userRating()
  {
    return $this->hasMany('UploadRating', 'upload_id', 'id');
  }

  public function tags()
  {
    return $this->hasMany('UploadTag', 'upload_id', 'id');
  }

  public function views()
  {
    return $this->hasMany('UploadView', 'upload_id', 'id');
  }

  public function countViews()
  {
    return $this->hasMany('UploadView', 'upload_id', 'id')->count();
  }

  public static function boot()
  {
    parent::boot();

    static::creating(function ($upload)
    {
      // Store internet media type
      $parser = new TypeParser;
      $upload->media_type = $parser->parse($upload->mimetype);
    });
  }

}
