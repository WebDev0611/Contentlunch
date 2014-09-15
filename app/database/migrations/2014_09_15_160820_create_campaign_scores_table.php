<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaignScoresTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('campaign_scores', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('campaign_id')->unsigned();
            $table->date('date');
            $table->float('quantity_score')->nullable();
            $table->float('quality_score')->nullable();
            $table->float('diversity_score')->nullable();
            $table->float('score')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->unique(['campaign_id', 'date']);
            $table->foreign('campaign_id')
                ->references('id')->on('campaigns')
                ->onDelete('cascade');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::drop('campaign_scores');
	}

}
