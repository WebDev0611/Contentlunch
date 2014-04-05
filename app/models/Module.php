<?php

use LaravelBook\Ardent\Ardent;

class Module extends Ardent {

  protected $table = 'modules';

  protected function getDateFormat()
  {
    return 'Y-m-d H:i:s';
  }

}
