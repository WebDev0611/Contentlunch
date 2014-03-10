<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAccountSettings extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Add fields to account table
		Schema::table('accounts', function($table) {
			$table->string('name')->default('');
			$table->string('address')->default('');
			$table->string('address_2')->default('');
			$table->string('city')->default('');
			$table->string('state')->default('');
			$table->string('phone')->default('');
			$table->integer('subscription')->default(0);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		// Remove fields
		Schema::table('accounts', function($table) {
			$table->dropColumn('name');
			$table->dropColumn('address');
			$table->dropColumn('address_2');
			$table->dropColumn('city');
			$table->dropColumn('state');
			$table->dropColumn('phone');
			$table->dropColumn('subscription');
		});
	}

}
