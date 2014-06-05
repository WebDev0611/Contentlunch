<?php

use Andrew13\Cabinet\CabinetUpload;

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

}
