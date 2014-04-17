<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AccountContentSettingsCreateTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Create a table for storing account content settings
		Schema::create('account_content_settings', function ($table) {
			$table->increments('id')->unsigned();
			$table->integer('account_id')->references('id')->on('account')->unique();
			$table->text('include_name');
			$table->text('allow_edit_date');
			$table->text('keyword_tags');
			$table->text('publishing_guidelines');
			$table->text('personas');
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
		// Drop the table
		Schema::drop('account_content_settings');
	}

}
