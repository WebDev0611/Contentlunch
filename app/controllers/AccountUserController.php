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
    $account->add_user(Input::get('user_id'));
    return array('message' => 'OK');
  }

  public function show($id)
  {
    // Restrict user is in account
    if ( ! $this->inAccount($id)) {
      return $this->responseAccessDenied();
    }
    $account = Account::find($id);
    return $account->getUsers();
  }

}
