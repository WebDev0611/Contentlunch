<?php

use Zizaco\Confide\ConfideUser;
use Zizaco\Entrust\HasRole;

class User extends ConfideUser {
  use HasRole;

  public $autoPurgeRedundantAttributes = true;

  public $forceEntityHydrationFromInput = true;

  protected $hidden = array('password', 'password_confirmation', 'confirmation_code');

  protected $fillable = array('username', 'email', 'first_name', 'last_name', 'confirmed', 'password',
    'password_confirmation',
    'address', 'address_2', 'city', 'state', 'phone', 'title', 'status', 'country');

  protected $guarded = array('id');

  public static $rules = array(
    'username' => 'required|unique:users,username',
    'email' => 'required|email',
    'password' => 'required|between:4,20|confirmed',
    'password_confirmation' => 'min:4'
  );

  protected $softDelete = true;

  protected function getDateFormat()
  {
    return 'Y-m-d H:i:s';
  }

  public function accounts()
  {
  	return $this->belongsToMany('Account')->withTimestamps();
  }

  public function zroles()
  {
    return $this->belongsToMany('Role', 'assigned_roles', 'id', 'user_id');
  }

  public function image()
  {
    return $this->hasOne('Upload', 'id', 'image');
  }

  /**
   * Save roles to this user
   * @param  array $roles Role ids
   */
  public function saveRoles($roles) {
    if ( ! empty($roles)) {
      $storeRoles = array();
      foreach ($roles as $role) {
        $storeRoles[] = $role['id'];
      }
      $this->roles()->sync($storeRoles);
    } else {
      $this->roles()->detach();
    }
  }

  /**
   * Get this user's roles in associative array id => :id, name = :name
   * @return array $roles
   */
  public function getRoles() {
    $ret = array();
    $roles = $this->roles;
    if ($roles) {
      foreach ($roles as $role) {
        $ret[] = array(
          'id' => $role->id,
          'name' => $role->name
        );
      }
    }
    return $ret;
  }

  public function scopeWithAccounts($query) {

  }

  public function scopeAccount($query, $id) {
    return $query->with('accounts')->where('accounts.id', $id);
    return $query->with(array('accounts' => function($query) use ($id) {
      $query->where('accounts.id', $id);
    }));
    return $query->accounts()->where('id', $id);
  }

  /**
   * Limit the query to users with assigned roles
   * @param  object $query
   * @param  array $roles Role ids to filter by
   * @return object $query
   */
  public function scopeRoles($query, $roles) {
    return $query->whereExists(function ($q) use ($roles) {
      $q->select(DB::raw(1))
        ->from('assigned_roles')
        ->whereIn('role_id', $roles)
        ->whereRaw('assigned_roles.user_id = users.id');
    });
  }

}
