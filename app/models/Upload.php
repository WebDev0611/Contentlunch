<?php

use Andrew13\Cabinet\CabinetUpload;
use webignition\InternetMediaType\Parser\TypeParser;

class Upload extends CabinetUpload {

  protected $softDelete = true;

  /**
   * Get the absolute url to the upload
   */
  public function getUrl()
  {
    $url = URL::asset(str_replace('/public/', '', $this->path) . $this->filename);
    // return non https
    $url = str_replace('https://', 'http://', $url);
    $url = str_replace(' ', '%20', $url);
    return $url;
  }

  public function getImageUrl($size)
  {
    //$url = URL::asset(str_replace('/public/', '', $this->path));
    $filepath = str_replace('/public/', '', $this->path . $this->filename);
    $url = URL::asset('/');
    // return non https
    $url = str_replace('https://', 'http://', $url);
    $url = str_replace(' ', '%20', $url);
    return $url .'image/'. $size .'/'. $filepath;
  }

  /**
   * Get the absolute file path to the upload
   */
  public function getAbsPath()
  {
    return base_path() . $this->path . $this->filename;
  }

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
