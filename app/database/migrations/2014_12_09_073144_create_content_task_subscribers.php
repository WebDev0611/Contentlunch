<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentTaskSubscribers extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('content_task_subscribers', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('content_task_id')->unsigned();
            $table->unique(['user_id', 'content_task_id']);
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('content_task_id')->references('id')->on('content_tasks');
        });

        Schema::create('campaign_task_subscribers', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('campaign_task_id')->unsigned();
            $table->unique(['user_id', 'campaign_task_id']);
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('campaign_task_id')->references('id')->on('campaign_tasks');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('content_task_subscribers');
		Schema::drop('campaign_task_subscribers');
	}

}
