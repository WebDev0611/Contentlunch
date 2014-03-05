<?php

use Zizaco\Confide\ConfideUser;
use Zizaco\Entrust\HasRole;

class User extends ConfideUser {
  use HasRole;

  public $autoPurgeRedundantAttributes = true;

  public $forceEntityHydrationFromInput = true;

  protected $hidden = array('password', 'password_confirmation', 'confirmation_code');

  protected $fillable = array('email', 'first_name', 'last_name', 'confirmed', 'password', 
    'address', 'address_2', 'city', 'state', 'phone', 'title', 'status', 'country');
  protected $guarded = array('id');

  public static $rules = array(
    'username' => 'unique:users,username',
    'email' => 'required|email',
    'password' => 'required|between:4,11|confirmed'
  );

  protected function getDateFormat()
  {
    return 'Y-m-d H:i:s';
  }

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

  public function scopeAccount($query, $id) {
    return $query->with('accounts')->where('accounts.id', $id);
    return $query->with(array('accounts' => function($query) use ($id) {
      $query->where('accounts.id', $id);
    }));
    return $query->accounts()->where('id', $id);
  }

}
