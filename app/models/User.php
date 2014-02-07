<?php

use Zizaco\Confide\ConfideUser;

class User extends ConfideUser {

  public static $rules = array(
    'username' => 'unique:users,username',
    'email' => 'required|email',
    'password' => 'required|between:4,11|confirmed'
  );

}
