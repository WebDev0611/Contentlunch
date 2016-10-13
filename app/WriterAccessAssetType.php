<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class WriterAccessAssetType extends Model {

  public $table = 'writer_access_asset_types';

  protected $softDelete = true;

  protected $fillable = array('writer_access_id', 'name');

  public static $rules = array(
      'writer_access_id' => 'required|unique:writer_access_asset_types,writer_access_id',
      'name' => 'required|unique:writer_access_asset_types,name'
  );

  public function writer_access_price()
  {
    return $this->hasMany('WriterAccessPrice', 'writer_access_id', 'asset_type_id');
  }


}