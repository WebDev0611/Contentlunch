<?php

/*
 * Migration to enable fields and tables required to do an agency version of CL
 *
 * Agency plan:
 *   An agency is a special kind of account
 *
 * Alter accounts table to add an account_type flag (standard, client, agency)
 * Alter accounts table to have a parent_id field that points a client to it's agency
 *
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AgencyAccounts extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('accounts', function($table)
		{
			$table->enum('account_type', array('single', 'client', 'agency'))->default("single");
			$table->integer('parent_id')->unsigned()->nullable();
			$table->foreign('parent_id')->references('id')->on('accounts');
		});

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('accounts', function($table)
		{
			$table->dropColumn('account_type');
			$table->dropColumn('parent_id');
		});
	}

}
