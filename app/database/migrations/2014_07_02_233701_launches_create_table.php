<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LaunchesCreateTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('launches', function ($table) {
      $table->increments('id');
      $table->integer('content_id')->references('id')->on('content')->onDelete('cascade');
      $table->integer('account_connection_id')->references('id')->on('account_connection_id')->onDelete('cascade');
      $table->boolean('success');
      $table->text('response')->nullable();
      $table->timestamps();
    });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('launches');
	}

}
