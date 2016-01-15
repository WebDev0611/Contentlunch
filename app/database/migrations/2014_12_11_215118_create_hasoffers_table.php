<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHasoffersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Id's from hasoffers don't appear to be all ints
		// so I'm making these text
		Schema::create('hasoffers', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('account_id')->unsigned();
            $table->text('transaction_id');
            $table->text('affiliate_id')->nullable();
            $table->text('offer_id')->nullable();
            $table->tinyInteger('status');
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP')); $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('hasoffers');
	}

}
