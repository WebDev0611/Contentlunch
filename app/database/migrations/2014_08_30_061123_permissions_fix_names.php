<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PermissionsFixNames extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Update the names of some permissions
		DB::table('permissions')
			->where('name', 'calendar_view_archive')
			->update([
				'display_name' => 'View archived content'
			]);

		DB::table('permissions')
			->where('name', 'create_execute_launch_content_other')
			->update([
				'display_name' => 'Launch Other User\'s Published Content'
			]);

		$id = DB::table('permissions')
			->where('name', 'create_view_launch_content_other')
			->pluck('id');
		DB::table('permission_role')
			->where('permission_id', $id)
			->delete();
		DB::table('permissions')
			->where('id', $id)
			->delete();
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
