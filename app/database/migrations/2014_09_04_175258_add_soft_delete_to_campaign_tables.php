<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSoftDeleteToCampaignTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('campaigns', function(Blueprint $table){
            $table->softDeletes();
        });

        Schema::table('campaign_comments', function(Blueprint $table){
            $table->softDeletes();
        });

        Schema::table('campaign_tags', function(Blueprint $table){
            $table->softDeletes();
        });

        Schema::table('campaign_tasks', function(Blueprint $table){
            $table->softDeletes();
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('campaigns', function(Blueprint $table){
            $table->dropSoftDeletes();
        });

        Schema::table('campaign_comments', function(Blueprint $table){
            $table->dropSoftDeletes();
        });

        Schema::table('campaign_tags', function(Blueprint $table){
            $table->dropSoftDeletes();
        });

        Schema::table('campaign_tasks', function(Blueprint $table){
            $table->dropSoftDeletes();
        });
	}

}
