<?php

use LaravelBook\Ardent\Ardent;

class Subscription extends Ardent {

  protected $table = 'account_subscription';

  protected function getDateFormat()
  {
    return 'Y-m-d H:i:s';
  }

}
