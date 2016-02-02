<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AccountFreemiumFlag extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('account_subscription', function($table) {
			$table->enum('subscription_type', array('freemium', 'trial', 'paid', 'complementary'))->default("freemium");
		});

		Schema::table('subscriptions', function($table){
            $table->string('name', 32)->default('unnamed');
			$table->boolean('active')->default(true);
		});


        // Mark the two bad plans as inactive
        foreach ([1,2] as $id) {
            $sub = Subscription::findOrFail($id);
            $sub ->active = false;
            $sub->save();
        }

        // Give plan #3 a name
        $sub = Subscription::findOrFail(3);
        $sub ->name = 'Professional';
        $sub ->monthly_price = 100;
        $sub->save();


        // Create the enterprise and agency plans
		$data = [
			[4, 999, 250, 10, 1, 'API, Premium Support, Custom Reporting, Advanced Security', 'Enterprise'],
            [5, 999, 100, 10, 1, 'API, Premium Support, Custom Reporting, Advanced Security', 'Agency'],
		];
		foreach ($data as $row) {
			$sub = new Subscription;
			$sub->subscription_level = $row[0];
			$sub->licenses = $row[1];
			$sub->monthly_price = $row[2];
			$sub->annual_discount = $row[3];
			$sub->training = $row[4];
			$sub->features = $row[5];
            $sub->name = $row[6];
			$sub->save();
		}


	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('account_subscription', function($table) {
            $table->dropColumn('subscription_type');
        });

        Schema::table('subscriptions', function($table){
            $table->dropColumn('name');
            $table->dropColumn('active');
        });

        $sub = Subscription::findOrFail(4);
        $sub->delete();

        $sub = Subscription::findOrFail(5);
        $sub->delete();

    }

}
