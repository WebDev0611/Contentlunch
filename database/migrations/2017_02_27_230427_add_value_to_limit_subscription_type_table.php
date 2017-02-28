<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddValueToLimitSubscriptionTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('limit_subscription_type', function (Blueprint $table) {
            $table->integer('value')
                ->after('subscription_type_id')
                ->unsigned()
                ->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('limit_subscription_type', function (Blueprint $table) {
            $table->dropColumn('value');
        });
    }
}
