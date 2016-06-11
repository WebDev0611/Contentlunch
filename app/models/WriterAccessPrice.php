<?php

use LaravelBook\Ardent\Ardent;

class WriterAccessPrice extends Ardent {

  public $table = 'writer_access_prices';

  protected $softDelete = true;

  protected $fillable = array('id', 'asset_type_id', 'writer_level', 'wordcount', 'fee' );

  public static $rules = array(
      'asset_type_id' => 'required',
      'writer_level' => 'required',
      'wordcount' => 'required',
      'fee' => 'required'
  );

  public function writer_access_asset_type()
  {
    return $this->hasOne('WriterAccessAssetType', 'writer_access_id', 'asset_type_id');
  }

}