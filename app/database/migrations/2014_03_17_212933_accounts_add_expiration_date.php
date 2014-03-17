<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AccountsAddExpirationDate extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Add expiration date
    Schema::table('accounts', function ($table) {
      $table->timestamp('expiration_date')->default('0000-00-00 00:00:00');
    });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('accounts', function ($table) {
      $table->dropColumn('expiration_date');
    });
	}

}
