<?php

class AccountUserController extends BaseController {

  public function store($id) {
    $account = Account::find($id);
    $account->add_user(Input::get('user_id'));
    return array('message' => 'OK');
  }

  public function show($id)
  {
    $account = Account::find($id);
    return $account->getUsers();
  }

}
