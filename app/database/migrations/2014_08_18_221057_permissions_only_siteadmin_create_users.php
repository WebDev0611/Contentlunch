<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PermissionsOnlySiteadminCreateUsers extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Only the site admin should be allowed to create new users
		$perm = Permission::where('name', 'settings_execute_users')->first();
		// Revoke from all builtin roles besides site_admin
		$roles = Role::where('builtin', 1)->where('name', '<>', 'site_admin')->get();
		foreach ($roles as $role) {
			$role->perms()->detach($perm->id);
		}
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

}
