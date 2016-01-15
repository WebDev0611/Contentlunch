<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ContentActivityCreateTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('content_activities', function ($table) {
      $table->increments('id');
      $table->integer('content_id')->references('id')->on('content')->onDelete('cascade');
      $table->integer('user_id')->references('id')->on('users')->onDelete('cascade');
      $table->string('activity');
      $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP')); $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
    });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('content_activities');
	}

}
