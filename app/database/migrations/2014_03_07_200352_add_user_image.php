<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserImage extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Add column to users that references their profile image
    Schema::table('users', function($table) {
      $table->integer('image')->nullable();
    });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		// Drop column
    Schema::table('users', function($table) {
      $table->dropColumn('image');
    });
	}

}
