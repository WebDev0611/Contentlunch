<?php

use Zizaco\Confide\ConfideUser;
use Zizaco\Entrust\HasRole;

class User extends ConfideUser {
  use HasRole;

  public $autoPurgeRedundantAttributes = true;

  //public $autoHydrateEntityFromInput = true;

  public $forceEntityHydrationFromInput = true;

  protected $hidden = array('password', 'password_confirmation', 'confirmation_code');

  protected $fillable = array('email', 'first_name', 'last_name', 'confirmed', 'password');
  protected $guarded = array('id');

  public static $rules = array(
    'username' => 'unique:users,username',
    'email' => 'required|email',
    'password' => 'required|between:4,11|confirmed'
  );

  public function accounts()
  {
  	return $this->belongsToMany('Account')->withTimestamps();
  }

  /**
   * Save roles to this user
   * @param  array $roles Role ids
   */
  public function saveRoles($roles) {
    if ( ! empty($roles)) {
      $this->roles()->sync($roles);
    } else {
      $this->roles()->detach();
    }
  }

  /**
   * Get this user's roles in associative array id => role name
   * @return array $roles
   */
  public function getRoles() {
    $ret = array();
    $roles = $this->roles;
    if ($roles) {
      foreach ($roles as $role) {
        $ret[$role->id] = $role->name;
      }
    }
    return $ret;
  }

  protected function getDateFormat()
  {
    return 'Y-m-d H:i:s';
  }

}
