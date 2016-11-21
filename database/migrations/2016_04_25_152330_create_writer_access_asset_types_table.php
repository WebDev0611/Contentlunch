<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateWriterAccessAssetTypesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('writer_access_asset_types', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
			$table->integer('writer_access_id');
			$table->string('name', 56)->default('');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('writer_access_asset_types');
	}

}
