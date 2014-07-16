<?php

use Zizaco\Confide\ConfideUser;
use Zizaco\Entrust\HasRole;

class User extends ConfideUser {
  use HasRole;

  public $autoPurgeRedundantAttributes = true;

  public $forceEntityHydrationFromInput = true;

  protected $hidden = array('password', 'password_confirmation', 'confirmation_code');

  protected $fillable = array('username', 'email', 'first_name', 'last_name', 'confirmed', 'password',
    'password_confirmation', 'hidden_announcements',
    'address', 'address_2', 'city', 'state', 'phone', 'title', 'status', 'country');

  protected $guarded = array('id', 'remember_token');

  public static $rules = array(
    'username' => 'required|unique:users,username',
    'email' => 'required|email',
    'password' => 'required|between:4,20|confirmed',
    'password_confirmation' => 'min:4'
  );

  protected $softDelete = true;

  public function getRememberToken()
  {
    return $this->remember_token;
  }

  public function setRememberToken($value)
  {
    $this->remember_token = $value;
  }

  public function getRememberTokenName()
  {
    return 'remember_token';
  }

  //protected function getDateFormat()
  //{
  //  return 'Y-m-d H:i:s';
  //}

  public function accounts()
  {
  	return $this->belongsToMany('Account', 'account_user', 'user_id', 'account_id')->withTimestamps();
  }

  public function content_tasks()
  {
    return $this->hasMany('ContentTask');
  }

  public function campaign_tasks()
  {
    return $this->hasMany('CampaignTask');
  }

  public function zroles()
  {
    return $this->belongsToMany('Role', 'assigned_roles', 'id', 'user_id');
  }

  public function image()
  {
    return $this->hasOne('Upload', 'id', 'image');
  }

  // forum stuff
  public function forum_threads()
  {
    return $this->hasMany('ForumThread');
  }

  public function thread_replies()
  {
    return $this->hasMany('ThreadReplies');
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

  /**
   * Get this user's account ID, should belong to one account
   */
  public function getAccountID()
  {
    $accounts = $this->accounts;
    if ($accounts) {
      foreach ($accounts as $account) {
        return $account->id;
      }
    }
    return null;
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

  public function beforeSave($forced = false)
  {
    if (is_array(@$this->hidden_announcements)) {
      $this->hidden_announcements = array_unique($this->hidden_announcements);
      $this->hidden_announcements = json_encode($this->hidden_announcements);
    }
  }

  public function toArray()
  {
    $values = parent::toArray();

    if (is_string(@$values['hidden_announcements'])) {
      $values['hidden_announcements'] = @json_decode($values['hidden_announcements'], true);
    }
    if (!@$values['hidden_announcements']) {
      $values['hidden_announcements'] = [];
    }

    return $values;
  }
}
