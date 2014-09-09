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
