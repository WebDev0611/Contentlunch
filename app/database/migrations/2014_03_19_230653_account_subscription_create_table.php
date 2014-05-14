<?php

use Illuminate\Database\Schema\Blueprint;
use Launch\Migration;

class AccountSubscriptionCreateTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Create table for account subscriptions
    Schema::create('account_subscription', function ($table) {
      $table->increments('id');
      $table->integer('account_id');
      $table->integer('subscription_level')->default(1);
      $table->integer('licenses')->nullable();
      $table->integer('monthly_price')->nullable();
      $table->integer('annual_discount')->nullable();
      $table->integer('training')->nullable();
      $table->text('features')->nullable();
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
		Schema::drop('account_subscription');
	}

}
