<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaignTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('title');
            $table->integer('status');
            $table->integer('campaign_type_id');
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_recurring')->unsigned(); // not negitive
            $table->text('description');
            $table->text('goals');
            $table->timestamps();
        });

        Schema::create('campaign_types', function ($table) {
            $table->increments('id');
            $table->string('name');
            $table->string('key');
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
        Schema::drop('campaigns');
        Schema::drop('campaign_types');
    }
}
