<?php

use Illuminate\Database\Schema\Blueprint;
use Launch\Migration;

class AccountsAddPaymentInfo extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Add column for storing payment details
    Schema::table('accounts', function ($table) {
      $table->string('payment_info')->nullable();
      $table->string('balanced_info')->nullable();
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
      $table->dropColumn('payment_info');
      $table->dropColumn('balanced_info');
    });
	}

}
