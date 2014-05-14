<?php

use Illuminate\Database\Schema\Blueprint;
use Launch\Migration;

class UsersAddRememberToken extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Add remember_token column, which is required for auth to remember
    // user's session
    Schema::table('users', function ($table) {
      $table->string('remember_token')->nullable();
    });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
    if (Schema::hasColumn('users', 'remember_token')) {
		  Schema::table('users', function ($table) {
        $table->dropColumn('remember_token');
      });
    }
	}

}
