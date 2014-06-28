<?php

class AccountRoleController extends BaseController {

  /**
   * Get the roles that belong to this account
   * @param integer $id Account ID
   */
  public function index($id)
  {
    // Restrict user is in account
    if ( ! $this->inAccount($id)) {
      return $this->responseAccessDenied();
    }
    $permissions = Permission::all();
    $roles = AccountRole::where('account_id', $id)->with('perms')->get();
    $modules = Account::find($id)->modules;
    foreach ($modules as $module) {
      $moduleNames[] = $module->name;
    }
    $moduleNames[] = 'home';
    $moduleNames[] = 'admin';
    $moduleNames[] = 'settings';
    if ($roles) {
      foreach ($roles as $role) {
        $rolePerms = array();
        // Attach each permission with access of "true" or "false"
        foreach ($permissions as $permission) {
          $access = 0;
          if ($role->perms) {
            foreach ($role->perms as $rolePerm) {
              if ($rolePerm->id == $permission->id) {
                $access = 1;
                break;
              }
            }
          }
          // Only return permission if account has access to the module
          if (in_array($permission->module, $moduleNames)) {
            $rolePerms[] = array(
              'name' => $permission->name,
              'display_name' => $permission->display_name,
              'access' => $access,
              'module' => $permission->module,
              'type' => $permission->type
            );
          }
        }
        $role->permissions = $rolePerms;
        unset($role->perms);
      }
    }
    return $roles;
  }

  public function show($id)
  {
    $permissions = Permission::all();
    $role = AccountRole::with('perms')->find($id);
    if ($role) {
      $rolePerms = array();
      foreach ($permissions as $permission) {
        $access = false;
        if ($role->perms) {
          foreach ($role->perms as $rolePerm) {
            if ($rolePerm->id == $permission->id) {
              $access = true;
              break;
            }
          }
        }
        $rolePerms[] = array(
          'name' => $permission->name,
          'display_name' => $permission->display_name,
          'access' => $access,
          'module' => $permission->module,
          'type' => $permission->type
        );
      }
      $role->permissions = $rolePerms;
      unset($role->perms);
      return $role;
    }
    return $this->responseError("Role not found");
  }

  public function showRole($account_id, $role_id)
  {
    return $this->show($role_id);
  }

  public function store($id)
  {
    // Restrict user is in account
    if ( ! $this->inAccount($id)) {
      return $this->responseAccessDenied();
    }
    $role = new AccountRole;
    $role->account_id = $id;
    $role->name = strtolower(str_replace(' ', '_', Input::get('display_name')));
    $role->display_name = Input::get('display_name');
    $role->status = Input::get('status');
    $role->global = 0;
    $role->builtin = 0;
    $role->deletable = 1;
    if ($role->save()) {
      $this->sync_permissions($role, Input::get('permissions'));
      return $this->show($role->id);
    }
    return $this->responseError($role->errors()->all(':message'));
  }

  public function update($accountId, $roleId)
  {
    // Restrict user is in account
    if ( ! $this->inAccount($accountId)) {
      return $this->responseAccessDenied();
    }
    $role = AccountRole::find($roleId);
    $role->display_name = Input::get('display_name');
    $role->status = Input::get('status');
    if ($role->updateUniques()) {
      $this->sync_permissions($role, Input::get('permissions'));
      return $this->show($role->id);
    }
    return $this->responseError($role->errors()->all(':message'));
  }

  protected function sync_permissions($role, $permissions = array())
  {
    $syncPerms = array();
    if ($permissions) {
      foreach ($permissions as $permission) {
        // Lookup by name
        if ($permission['access']) {
          $permModel = Permission::find_by_name($permission['name']);
          $syncPerms[] = $permModel->id;
        }
      }
    }
    $role->perms()->sync($syncPerms);
  }

  public function destroy($accountId, $roleId)
  {
    // Restrict user is in account
    if ( ! $this->inAccount($accountId)) {
      return $this->responseAccessDenied();
    }
    $role = AccountRole::find($roleId);
    // Don't delete non deletable roles
    if ( ! $role->deletable) {
      return $this->responseError("Unable to delete this role.", 403);
    }
    // Don't delete role if there are users attached to it
    $users = $role->users->toArray();
    if ( ! empty($users)) {
      // Return a list of users with this role
      $names = [];
      foreach ($users as $user) {
        $names[] = $user['first_name'] .' '. $user['last_name'];
      }
      $userNames = implode(', ', $names);
      return $this->responseError("This User Role cannot be deleted. These users are assigned this role: ". $userNames);
    }
    if ($role->delete()) {
      return array('success' => 'OK');
    }
    return $this->responseError("Couldn't delete role.");
  }

}
