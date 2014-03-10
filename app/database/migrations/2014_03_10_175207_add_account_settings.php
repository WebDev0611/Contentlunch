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
			$table->string('name')->nullable();
			$table->string('address')->nullable();
			$table->string('address_2')->nullable();
			$table->string('city')->nullable();
			$table->string('state')->nullable();
			$table->string('phone')->nullable();
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
