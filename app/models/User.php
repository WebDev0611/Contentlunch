<?php

use Zizaco\Confide\ConfideUser;
use Zizaco\Entrust\HasRole;

class User extends ConfideUser {
  use HasRole;

  public $autoPurgeRedundantAttributes = true;

  //public $autoHydrateEntityFromInput = true;

  public $forceEntityHydrationFromInput = true;

  protected $hidden = array('password', 'password_confirmation', 'confirmation_code');

  protected $guarded = array('id', 'password');

  public static $rules = array(
    'username' => 'unique:users,username',
    'email' => 'required|email',
    'password' => 'required|between:4,11|confirmed'
  );

  public function accounts()
  {
  	return $this->belongsToMany('Account')->withTimestamps();
  }

}
