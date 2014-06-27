<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ConferencesSetup extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('conferences', function ($table) {
      $table->increments('id');
      $table->text('description');
      $table->integer('status');
      $table->string('topic');
      $table->string('consultant');
      $table->timestamp('scheduled_date')->nullable();
      $table->timestamp('date_1')->nullable();
      $table->timestamp('date_2')->nullable();
      $table->text('date_other_comment')->nullable();
      $table->string('replay_link')->nullable();
      $table->integer('user_id')->references('id')->on('users')->onDelete('cascade');
      $table->integer('account_id')->references('id')->on('accounts')->onDelete('cascade');
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
		Schema::drop('conferences');
	}

}
