<?php

use Zizaco\Entrust\EntrustPermission;

class Permission extends EntrustPermission
{

  public static function find_by_name($name)
  {
    return static::where('name', $name)->first();
  }

}
