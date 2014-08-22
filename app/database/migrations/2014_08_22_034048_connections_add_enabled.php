<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ConnectionsAddEnabled extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('connections', function ($table) {
			$table->boolean('enabled')->default(1);
		});
		// Disable these connections:
		Connection::whereIn('provider', [
			'drupal', 'joomla', 'salesforce', 'trapit', 'papershare'
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
		Schema::table('connections', function ($table) {
			$table->dropColumn('enabled');
		});
	}

}
