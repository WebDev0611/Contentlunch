<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SubscriptionsCreateTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Create subscriptions table
    Schema::create('subscriptions', function ($table) {
      $table->increments('id');
      $table->integer('licenses');
      $table->integer('monthly_price');
      $table->integer('annual_discount');
      $table->integer('training');
      $table->text('features');
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
	  Schema::drop('subscriptions');
	}

}
