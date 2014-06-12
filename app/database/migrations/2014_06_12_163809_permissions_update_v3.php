<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PermissionsUpdateV3 extends Migration {

  protected $permissions = [
    ['create_execute_archive_restore_content_own', 'Archive Restore Own Content', 
      ['site_admin', 'manager', 'creator', 'client', 'editor']],
    ['create_execute_archive_restore_content_other', 'Archive Restore Other Content',
      ['site_admin', 'manager']],
    ['collaborate_delete_tasks', 'Collaborate delete tasks',
      ['site_admin', 'manager', 'editor']],
    ['promote_content_own', 'Promote Own Content', ['site_admin', 'manager', 'creator', 'client', 'editor']],
    ['promote_content_other', 'Promote Other Content',
      ['site_admin', 'manager', 'editor']],
    ['promote_campaign_own', 'Promote Own Campaign', ['site_admin', 'manager', 'creator', 'client', 'editor']],
    ['promote_campaign_other', 'Promote Other Campaign', ['site_admin', 'manager', 'editor']]
  ];

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    foreach ($this->permissions as $permission) {
      list($name, $displayName, $assignRoles) = $permission;
      // Permission keys start with moduleName_
      $parts = explode('_', $name);
      list($module, $type) = $parts;
      $p = new Permission;
      $p->name = $name;
      $p->display_name = $displayName;
      $p->module = $module;
      $p->type = $type;
      $p->save();
      // Attach this permission to roles
      if ($assignRoles) {
        foreach ($assignRoles as $roleName) {
          // Assign permission to all account roles as well as built-in roles
          $roles = Role::where('name', $roleName)->get();
          foreach ($roles as $role) {
            $role->perms()->attach($p->id);
          }
        }
      }
    }
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    $roles = Role::all();
    foreach ($this->permissions as $permission) {
      list($name, $displayName, $assignRoles) = $permission;
      $p = Permission::where('name', $name)->first();
      // Detach permission from roles
      foreach ($roles as $role) {
        $role->perms()->detach($p->id);
      }
      // Delete permission
      $p->delete();
    }
  }

}
