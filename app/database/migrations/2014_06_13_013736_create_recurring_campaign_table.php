<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecurringCampaignTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recurring_campaigns', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('campaign_id');
            $table->timestamps();
        });

        Schema::table('campaigns', function (Blueprint $table) {
            $table->integer('recurring_id')->unsigned()->nullable()->after('is_recurring');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('recurring_campaigns');

        Schema::table('campaigns', function (Blueprint $table) {
            $table->dropColumn('recurring_id');
        });
    }

}
