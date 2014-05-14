<?php

use Illuminate\Database\Schema\Blueprint;
use Launch\Migration;

class ConnectionsCreateTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('connections', function ($table) {
			$table->increments('id');
			$table->string('name');
			$table->string('provider');
			$table->string('type');
			$table->timestamps();
		});

		// Add connections
		foreach ([
      ['Dropbox', 'dropbox', 'content'],
      ['Facebook', 'facebook', 'content'],
      ['Google +', 'google', 'content'],
			['Hubspot', 'hubspot', 'content'],
			['Linkedin', 'linkedin', 'content'],
      ['Salesforce', 'salesforce', 'content'],
      ['Twitter', 'twitter', 'content'],
			['Wordpress', 'wordpress', 'content'],
			['SEO Ultimate', 'seo-ultimate', 'seo'],
			['Sales Machine', 'sales-machine', 'seo']
		] as $data) {
			$connection = new Connection;
			$connection->name = $data[0];
			$connection->provider = $data[1];
			$connection->type = $data[2];
			$connection->save();
      $this->note('Created connection type: '. $connection->provider . ': '. $connection->name);
		}
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('connections');
	}

}
