<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUniqueIndexToAccountSubscriptionAccountId extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Clean up records with the same account id to be able to
		// add a unique rule on the table
		// Keep the latest record for each account id
		$subs = AccountSubscription::orderBy('id', 'asc')->get();
		$ids = [];
		foreach ($subs as $sub) {
			$ids[$sub->account_id] = $sub->id;
		}
		$ids = array_values($ids);
		DB::table('account_subscription')
			->whereNotIn('id', $ids)
			->delete();
        Schema::table('account_subscription', function(Blueprint $table){
            $table->unique('account_id');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('account_subscription', function(Blueprint $table){
            $table->dropUnique('account_subscription_account_id_unique');
        });
	}

}
