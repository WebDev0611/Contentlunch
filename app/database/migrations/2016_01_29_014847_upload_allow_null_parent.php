<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UploadAllowNullParent extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement('ALTER TABLE `uploads` MODIFY `parent_id` INTEGER UNSIGNED NULL;');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::statement('ALTER TABLE `uploads` MODIFY `parent_id` INTEGER UNSIGNED NOT NULL;');
	}

}
