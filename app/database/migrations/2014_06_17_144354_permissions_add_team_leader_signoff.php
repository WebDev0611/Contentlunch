<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PermissionsAddTeamLeaderSignoff extends Migration {

  protected $permissions = [
    ['create_execute_team_leader_signoff', 'Team Leader Signoff', 
      ['site_admin', 'manager']],
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
