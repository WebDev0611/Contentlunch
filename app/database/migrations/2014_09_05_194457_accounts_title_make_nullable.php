<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AccountsTitleMakeNullable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement("ALTER TABLE `accounts` CHANGE COLUMN `title` `title` VARCHAR(255) NULL DEFAULT NULL");
		DB::statement('DROP INDEX `accounts_title_unique` ON `accounts`');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::statement("ALTER TABLE `accounts` CHANGE COLUMN `title` `title` VARCHAR(255) NOT NULL");
	}

}
