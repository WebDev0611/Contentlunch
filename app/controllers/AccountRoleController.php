<?php

class AccountRoleController extends BaseController {

  /**
   * Get the roles that belong to this account
   * @param integer $id Account ID
   */
  public function index($id)
  {
    $roles = AccountRole::where('account_id', $id)->get();
    return $roles;
  }

  public function store($id)
  {
    $role = new AccountRole;
    $role->account_id = $id;
    $role->name = strtolower(str_replace(' ', '_', Input::get('display_name')));
    $role->display_name = Input::get('display_name');
    $role->status = Input::get('status');
    $role->global = 0;
    $role->builtin = 0;
    $role->deletable = 1;
    if ($role->save()) {
      return $role;
    }
    return $this->responseError($role->errors()->all(':message'));
  }

  public function update($accountId, $roleId)
  {
    $role = AccountRole::find($roleId);
    $role->display_name = Input::get('display_name');
    $role->status = Input::get('status');
    if ($role->updateUniques()) {
      return $role;
    }
    return $this->responseError($role->errors()->all(':message'));
  }

  public function destroy($accountId, $roleId)
  {
    $role = AccountRole::find($roleId);
    // Don't delete non deletable roles
    if ( ! $role->deletable) {
      return $this->responseError("Unable to delete this role.", 401);
    }
    if ($role->delete()) {
      return array('success' => 'OK');
    }
    return $this->responseError("Couldn't delete role.");
  }

}
