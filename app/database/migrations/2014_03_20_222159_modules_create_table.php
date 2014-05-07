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

    // Insert application's modules
    $data = [
      ['create', 'CREATE'],
      ['calendar', 'CALENDAR'],
      ['launch', 'LAUNCH'],
      ['measure', 'MEASURE'],
      ['collaborate', 'COLLABORATE'],
      ['consult', 'CONSULT']
    ];
    foreach ($data as $row) {
      $module = new Module;
      $module->name = $row[0];
      $module->title = $row[1];
      $module->save();
      echo 'Created module: '. $module->name . PHP_EOL;
    }

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
