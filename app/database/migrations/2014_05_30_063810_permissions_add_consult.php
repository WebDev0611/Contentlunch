<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PermissionsAddConsult extends Migration {

  protected $permissions = [
    // Key, Display name, Default roles to assign permissions to
    // Consult
    ['consult_view_library', 'Access to Create Library (Files & Folders)',
      ['manager', 'creator']],
    ['consult_edit_library', 'Access to Create Library (Files & Folders)',
      ['manager', 'creator']],
    ['consult_execute_library_new_folder', 'Create a new Folder',
      ['manager', 'creator']],
    ['consult_execute_forum_create', 'Access to Create User Forum',
      ['manager', 'creator']],
    ['consult_execute_video_create', 'Access to Create Video Conference',
      ['manager', 'creator']]
  ];

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Add permissions for the consult module
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
