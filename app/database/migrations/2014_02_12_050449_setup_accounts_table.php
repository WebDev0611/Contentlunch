<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SetupAccountsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Creates the accounts table
		Schema::create('accounts', function($table)
		{
			$table->increments('id')->unsigned();
			$table->string('title')->unique();
			$table->boolean('active')->default(false);
			$table->timestamps();
		});

		// Create the accounts users table (many to many relation)
		Schema::create('account_user', function($table)
		{
			$table->increments('id')->unsigned();
			$table->integer('user_id')->unsigned();
			$table->integer('account_id')->unsigned();
			$table->timestamps();
			$table->foreign('user_id')->references('id')->on('users');
			$table->foreign('account_id')->references('id')->on('accounts');
			$table->unique(array('user_id', 'account_id'));
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('account_user', function(Blueprint $table) {
			$table->dropForeign('account_user_user_id_foreign');
			$table->dropForeign('account_user_account_id_foreign');
		});

		Schema::drop('account_user');
		Schema::drop('accounts');
	}

}
