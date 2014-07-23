<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ContentAllowBodyNull extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
	  DB::statement("ALTER TABLE `content` CHANGE COLUMN `body` `body` TEXT NULL DEFAULT NULL");
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::statement("ALTER TABLE `content` CHANGE COLUMN `body` `body` TEXT NOT NULL");
	}

}
