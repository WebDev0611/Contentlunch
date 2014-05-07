<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CampaignsCreateTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Create campaign types table
		Schema::create('campaign_types', function ($table) {
			$table->increments('id');
			$table->string('key');
			$table->string('name');
		});

		// Insert campaign types
		foreach ([
      'advertising' => 'Advertising Campaign',
      'branding-promotion' => 'Branding Promotion',
      'content-marketing' => 'Content Marketing Campaign',
      'customer-nurture' => 'Customer Nurture',
      'email-nurture' => 'Email Nurture Campaign',
      'nontrade-event' => 'Event (Non Trade Show)',
      'partner-event' => 'Partner Event',
      'podcast-series' => 'Podcast Series',
      'product-launch' => 'Product Launch',
      'sales' => 'Sales Campaign',
      'thought-leadership' => 'Thought Leadership Series',
      'tradeshow-event' => 'Trade Show Event',
      'webinar' => 'Webinar'
    ] as $key => $name) {
			$type = new CampaignType;
			$type->key = $key;
			$type->name = $name;
			$type->save();
      echo 'Created Campaign type: '. $type->key .': '. $type->name . PHP_EOL;
    }

    // Create campaign tags table
    Schema::create('campaign_tags', function ($table) {
    	$table->increments('id');
    	$table->string('tag');
    	$table->integer('campaign_id');
    	$table->timestamps();
    });

		// Create campaigns table
		Schema::create('campaigns', function ($table) {
			$table->increments('id');
			$table->integer('account_id');
			$table->integer('user_id');
			$table->string('title');
			$table->integer('status');
			$table->integer('campaign_type_id');
			$table->timestamp('start_date');
			$table->timestamp('end_date');
			$table->boolean('is_recurring');
			$table->text('description');
			$table->text('goals');
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
		Schema::drop('campaign_tags');
	}

}
