<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModulesCreateTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Creates the modules table
    Schema::create('modules', function ($table) {
      $table->increments('id')->unsigned();
      $table->string('name')->unique();
      $table->string('title');
      $table->timestamps();
    });

    // Creates the account_module table
    Schema::create('account_module', function ($table) {
      $table->increments('id')->unsigned();
      $table->integer('account_id')->references('id')->on('account');
      $table->integer('module_id')->references('id')->on('modules');
    });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		// Drops the modules table
    Schema::drop('account_module');
    Schema::drop('modules');
	}

}
