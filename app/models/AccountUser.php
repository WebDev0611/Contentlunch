<?php

use LaravelBook\Ardent\Ardent;

class AccountUser extends Ardent {

  protected $table = 'account_user';

  protected function getDateFormat()
  {
    return 'Y-m-d H:i:s';
  }

}
