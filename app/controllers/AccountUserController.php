<?php

class AccountUserController extends BaseController {

  public function store($id) {
    // Restrict to create_new_user permission
    if ( ! $this->hasAbility(array(), array('settings_execute_users'))) {
      return $this->responseAccessDenied();
    }
    // Restrict to in account
    if ( ! $this->inAccount($id)) {
      return $this->responseAccessDenied();
    }
    $account = Account::find($id);
    $users = $account->users;
    if ($users) {
      foreach ($users as $user) {
        if ($user['id'] == Input::get('user_id')) {
          return ['message' => 'OK'];
        }
      }
    }
    $account->users()->attach(Input::get('user_id'));
    return ['message' => 'OK'];
  }

  /**
   * Return a list of users
   */
  public function show($id)
  {
    // Restrict user is in account
    $account = Account::find($id);
    if ( ! $account || ! $this->inAccount($id)) {
      return $this->responseAccessDenied();
    }
    $query = $account->users()
      ->with('roles')
      ->with('accounts')
      ->with('image');

    if (Input::has('permission')) {
      // User must have ALL passed permissions
      $query->whereHas('roles', function ($q) {
        $perms = explode(',', Input::get('permission'));
        foreach ($perms as $p) {
          $q->whereHas('perms', function ($q) use ($p) {
            $q->where('permissions.name', trim($p));
          });
        }
      }); 
    }
    return $query->get();
  }

}
