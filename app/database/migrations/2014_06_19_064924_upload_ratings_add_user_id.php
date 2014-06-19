<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UploadRatingsAddUserId extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('upload_ratings', function ($table) {
      $table->integer('user_id')->references('id')->on('users')->onDelete('cascade');
      $table->unique(['upload_id', 'user_id']);
    });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropUnique('upload_ratings_upload_id_user_id');
    Schema::table('upload_ratings', function ($table) {
      $table->dropColumn('user_id');
    });
	}

}
