<?php

use LaravelBook\Ardent\Ardent;

class AccountSubscription extends Ardent {

  protected $table = 'account_subscription';

  protected $fillable = [
  	'account_id'
  ];


  public function account()
  {
    return $this->belongsTo('Account','account_id','id');
  }

  //protected function getDateFormat()
  //{
  //  return 'Y-m-d H:i:s';
  //}

}
