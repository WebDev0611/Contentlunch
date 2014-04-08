<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RolesAddGlobalAndAccountId extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Add columns: global, account_id
    Schema::table('roles', function ($table) {
      $table->string('display_name')->nullable();
      $table->boolean('global')->default(false);
      $table->integer('account_id')->nullable();
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
    Schema::table('roles', function ($table) {
      $table->dropColumn('display_name');
      $table->dropColumn('global');
      $table->dropColumn('account_id');
    });
	}

}
