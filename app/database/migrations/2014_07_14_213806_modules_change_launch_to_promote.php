<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModulesChangeLaunchToPromote extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Change launch to promote in the modules table
    DB::table('modules')
      ->where('name', 'launch')
      ->update([
        'name' => 'promote',
        'title' => 'PROMOTE'
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
