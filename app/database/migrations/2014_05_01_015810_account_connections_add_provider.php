<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AccountConnectionsAddProvider extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Add provider column to account_connections
		Schema::table('account_connections', function ($table) {
			$table->string('provider')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		// Drop provider column
		Schema::table('account_connections', function ($table) {
			$table->dropColumn('provider');
		});
	}

}
