<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ContentAddFiles extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Add column for main content file upload
    Schema::table('content', function ($table) {
      $table->integer('upload_id')->nullable();
    });

    // Add column to uploads that joins to accounts table
    Schema::table('uploads', function ($table) {
      $table->integer('account_id')->nullable();
    });

    // Add join table for extra file uploads
    Schema::create('content_uploads', function ($table) {
      $table->increments('id');
      $table->integer('content_id');
      $table->integer('upload_id');
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
		Schema::drop('content_uploads');
    Schema::table('content', function ($table) {
      $table->dropColumn('upload_id');
    });
    Schema::table('uploads', function ($table) {
      $table->dropColumn('account_id');
    });
	}

}
