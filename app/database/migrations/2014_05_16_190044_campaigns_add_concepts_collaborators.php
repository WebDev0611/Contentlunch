<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CampaignsAddConceptsCollaborators extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Add new columns needed on the campaigns table
    Schema::table('campaigns', function ($table) {
      $table->text('concept')->nullable();
      $table->string('color')->nullable();
    });

    // Add new table for collabs
    Schema::create('campaign_collaborators', function ($table) {
      $table->increments('id');
      $table->integer('campaign_id');
      $table->integer('user_id');
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
    Schema::table('campaigns', function ($table) {
      $table->dropColumn('concept');
      $table->dropColumn('color');
    });
		Schema::drop('campaign_collaborators');
	}

}
