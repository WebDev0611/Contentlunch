<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPaymentDateToAccountsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('accounts', function($table)
		{
		    $table->date('payment_date')->nullable()->default(NULL);
		    $table->index(array('active', 'payment_date'));
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('accounts', function($table)
		{
		    $table->dropColumn('payment_date');
		    $table->dropIndex('accounts_active_payment_date_index');
		});
	}

}
