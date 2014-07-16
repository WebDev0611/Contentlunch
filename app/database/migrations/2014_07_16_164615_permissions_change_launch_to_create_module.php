<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PermissionsChangeLaunchToCreateModule extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Change launch permissions from launch module to create module
    DB::table('permissions')
      ->where('name', 'launch_execute_content_own')
      ->update([
      'name' => 'create_execute_launch_content_own',
      'module' => 'create'
    ]);
    DB::table('permissions')
      ->where('name', 'launch_view_content_other')
      ->update([
      'name' => 'create_view_launch_content_other',
      'module' => 'create'
    ]);
    DB::table('permissions')
      ->where('name', 'launch_execute_content_other')
      ->update([
      'name' => 'create_execute_launch_content_other',
      'module' => 'create'
    ]);
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
