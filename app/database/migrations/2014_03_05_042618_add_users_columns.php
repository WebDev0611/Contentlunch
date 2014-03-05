<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUsersColumns extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Add fields to user table
    Schema::table('users', function($table) {
      $table->string('address')->default('');
      $table->string('address_2')->default('');
      $table->string('city')->default('');
      $table->string('state')->default('');
      $table->string('phone')->default('');
      $table->integer('status')->default(0);
      $table->string('title')->default('');
    });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		// Remove columns
    Schema::table('users', function($table) {
      $table->dropColumn('address');
      $table->dropColumn('address_2');
      $table->dropColumn('city');
      $table->dropColumn('state');
      $table->dropColumn('phone');
      $table->dropColumn('status');
      $table->dropColumn('title');
    });
	}

}
