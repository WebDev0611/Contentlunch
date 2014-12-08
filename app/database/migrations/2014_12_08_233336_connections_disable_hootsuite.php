<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ConnectionsDisableHootsuite extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Connection::whereIn('provider', [
			'hootsuite'
		])->update([
			'enabled' => 0
		]);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Connection::whereIn('provider', [
			'hootsuite'
		])->update([
			'enabled' => 1
		]);
	}

}
