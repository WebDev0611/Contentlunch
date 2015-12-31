<?php

use Illuminate\Database\Schema\Blueprint;
use Launch\Migration;

class AccountConnectionsCreateTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Create table for storing account's connections
		Schema::create('account_connections', function ($table) {
			$table->increments('id');
			$table->integer('account_id');
			$table->integer('connection_id');
			$table->string('name');
			$table->integer('status');
			$table->text('settings');
			$table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP')); $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		// Drop account_connections table
		Schema::drop('account_connections');
	}

}
