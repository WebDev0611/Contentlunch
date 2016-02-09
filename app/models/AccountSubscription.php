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


  public function subscription()
  {
    return $this->belongsTo('Subscription', 'subscription_level', 'subscription_level');
  }

  //protected function getDateFormat()
  //{
  //  return 'Y-m-d H:i:s';
  //}

}
