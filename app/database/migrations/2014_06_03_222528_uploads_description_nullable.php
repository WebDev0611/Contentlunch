<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UploadsDescriptionNullable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('uploads', function ($table) {
      DB::unprepared("ALTER TABLE `uploads` MODIFY COLUMN `description` text NULL");
    });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('uploads', function ($table) {
      DB::unprepared("ALTER TABLE `uploads` MODIFY COLUMN `description` text NOT NULL");
    });
	}

}
