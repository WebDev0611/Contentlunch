<?php

use Launch\Migration;
use Illuminate\Database\Schema\Blueprint;

class ConnectionsAddContentConnections extends Migration {

  // Connections to add
  protected $connections = [
    ['Soundcloud', 'soundcloud', 'content'],
    ['Tumblr', 'tumblr', 'content'],
    ['Youtube', 'youtube', 'content'],
    ['Slideshare', 'slideshare', 'content'],
    ['Curata', 'curata', 'content'],
    ['Papershare', 'papershare', 'content'],
    ['Trap-it', 'trapit', 'content']
  ];

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Add connections
    foreach ($this->connections as $data) {
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
		foreach ($this->connections as $data) {
      $connection = Connection::where('provider', $data[1])->first();
      if ($connection) $connection->delete();
    }
	}

}
