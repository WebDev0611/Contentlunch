<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateWriterAccessPricesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('writer_access_prices', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('asset_type_id');
			$table->integer('writer_level');
			$table->integer('wordcount');
			$table->double('fee');
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
		Schema::drop('writer_access_prices');
	}

}
