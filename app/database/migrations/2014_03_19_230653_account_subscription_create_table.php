<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
      $table->boolean('auto_renew')->default(false);
      $table->timestamp('expiration_date')->nullable();
      $table->integer('licenses')->default(0);
      $table->string('payment_type', 6)->nullable();
      $table->integer('subscription_id')->default(0);
      $table->integer('monthly_price')->nullable();
      $table->integer('annual_discount')->nullable();
      $table->integer('training')->nullable();
      $table->text('features')->nullable();
      $table->string('token')->nullable();
      $table->boolean('yearly_payment')->default(false);
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
