<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UploadsViewsCreate extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('upload_views', function ($table) {
      $table->increments('id');
      $table->integer('upload_id')->references('id')->on('uploads')->onDelete('cascade');
      $table->integer('user_id')->references('id')->on('users')->onDelete('cascade');
      $table->timestamps();
      $table->unique('upload_id', 'user_id');
    });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('upload_views');
	}

}
