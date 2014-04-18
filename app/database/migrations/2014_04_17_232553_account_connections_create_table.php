<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
			$table->increments('id')->unsigned();
			$table->integer('account_id')->references('id')->on('accounts');
			$table->string('name');
			$table->integer('status');
			$table->string('type');
			$table->text('settings');
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
		// Drop account_connections table
		Schema::drop('account_connections');
	}

}
