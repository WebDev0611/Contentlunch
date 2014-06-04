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
      $db = Config::get('database.default');
      echo ' DB TYPE: '. $db .' ';
      if ($db == 'sqlserv') {
        DB::unprepared("ALTER TABLE [uploads] ALTER COLUMN [description] text NULL");
      } else {
        DB::unprepared("ALTER TABLE `uploads` MODIFY COLUMN `description` text NULL");
      }
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
      $db = Config::get('database.default');
      if ($db == 'sqlserv') {
        DB::unprepared("ALTER TABLE [uploads] ALTER COLUMN [description] text NOT NULL");
      } else {
        DB::unprepared("ALTER TABLE `uploads` MODIFY COLUMN `description` text NOT NULL");
      }
    });
	}

}
