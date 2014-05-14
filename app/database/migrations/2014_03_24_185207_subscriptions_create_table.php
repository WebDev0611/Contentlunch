<?php

use Illuminate\Database\Schema\Blueprint;
use Launch\Migration;

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
      $table->integer('subscription_level');
      $table->integer('licenses');
      $table->integer('monthly_price');
      $table->integer('annual_discount');
      $table->integer('training');
      $table->text('features');
      $table->timestamps();
    });

    // Insert subscriptions for the application
    $data = [
      [1, 5, 300, 10, 1, 'None'],
      [2, 10, 500, 10, 1, 'API, Premium Support'],
      [3, 20, 700, 10, 1, 'API, Premium Support, Custom Reporting, Advanced Security']
    ];
    foreach ($data as $row) {
      $sub = new Subscription;
      $sub->subscription_level = $row[0];
      $sub->licenses = $row[1];
      $sub->monthly_price = $row[2];
      $sub->annual_discount = $row[3];
      $sub->training = $row[4];
      $sub->features = $row[5];
      $sub->save();
      $this->note('Created subscription: '. $sub->subscription_level);
    }
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
