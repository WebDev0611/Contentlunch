<?php

use LaravelBook\Ardent\Ardent;

class Subscription extends Ardent {

  protected $table = 'subscriptions';

  protected $fillable = array('features');

  protected function getDateFormat()
  {
    return 'Y-m-d H:i:s';
  }

}