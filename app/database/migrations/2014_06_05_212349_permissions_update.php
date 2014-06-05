<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PermissionsUpdate extends Migration {

  protected $permissions = [
    ['content_delete', 'Delete Content', 
      ['site_admin', 'manager']],
    ['settings_edit_account_settings', 'Edit Account Settings',
      ['site_admin']],
    ['settings_view_content_settings', 'Edit Content Settings',
      ['site_admin']],
    ['settings_edit_content_settings', 'Edit Content Settings',
      ['site_admin']],
    ['settings_edit_seo_settings', 'Edit SEO Settings',
      ['site_admin']],
    ['settings_execute_users', 'Edit Users', ['global_admin']],
    ['settings_view_profiles', 'View Profiles', ['global_admin']],
    ['settings_edit_profiles', 'Edit Profiles', ['global_admin']]
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

    // Remove no longer needed permissions
    foreach (['settings_view_personas', 'settings_edit_personas'] as $name) {
      $id = DB::table('permissions')->where('name', $name)->pluck('id');
      DB::table('permission_role')->where('permission_id', $id)->delete();
      DB::table('permissions')->where('id', $id)->delete();
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
