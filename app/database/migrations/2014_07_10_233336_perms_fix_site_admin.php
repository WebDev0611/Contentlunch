<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PermsFixSiteAdmin extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Make sure site admin has all permissions
    $all = Permission::where('module', '<>', 'admin')->get();
    // Get builtin and account specific site_admin roles
    $roles = Role::where('name', 'site_admin')->get();
    foreach ($all as $permission) {
      foreach ($roles as $role) {
        $role->perms()->attach($permission->id);
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
		//
	}

}
