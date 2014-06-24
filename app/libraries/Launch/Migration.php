<?php namespace Launch;

use Illuminate\Database\Migrations\Migration as IllMigration;

class Migration extends IllMigration {

  protected function note($note)
  {
    if (app()->environment() != 'testing') {
      print $note . PHP_EOL;
    }
  }

  protected function upPermissions()
  {
    foreach ($this->permissions as $permission) {
      list($name, $displayName, $assignRoles) = $permission;
      // Permission keys start with moduleName_
      $parts = explode('_', $name);
      list($module, $type) = $parts;
      $p = new \Permission;
      $p->name = $name;
      $p->display_name = $displayName;
      $p->module = $module;
      $p->type = $type;
      $p->save();
      // Attach this permission to roles
      if ($assignRoles) {
        foreach ($assignRoles as $roleName) {
          // Assign permission to all account roles as well as built-in roles
          $roles = \Role::where('name', $roleName)->get();
          foreach ($roles as $role) {
            $role->perms()->attach($p->id);
          }
        }
      }
    }
  }

  protected function downPermissions()
  {
    $roles = \Role::all();
    foreach ($this->permissions as $permission) {
      list($name, $displayName, $assignRoles) = $permission;
      $p = \Permission::where('name', $name)->first();
      // Detach permission from roles
      foreach ($roles as $role) {
        $role->perms()->detach($p->id);
      }
      // Delete permission
      $p->delete();
    }
  }

}