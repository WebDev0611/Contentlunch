<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BalancedAddLogging extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Add a table for tracking payments made on accounts
		Schema::create('payments', function ($table) {
			$table->increments('id');
			$table->integer('account_id')->references('id')->on('accounts')->onDelete('cascade');
			$table->integer('user_id')->references('id')->on('users')->onDelete('cascade');
			$table->text('response');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('payments');
	}

}
