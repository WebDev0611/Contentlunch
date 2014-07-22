<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AccountConnectionsAddIdentifier extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('account_connections', function ($table) {
      $table->string('identifier')->nullable();
    });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('account_connections', function ($table) {
      $table->dropColumn('identifier');
    });
	}

}
