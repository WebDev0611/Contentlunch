<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Launch\Connections\API\ConnectionConnector;

class AccountConnectionAddExternalId extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('account_connections', function ($table) {
			$table->string('external_id')->nullable();
		});

		// Attempt to get external ids for all existing connections
		$connections = AccountConnection::with('connection')->get();
		$logs = [];
		foreach ($connections as $connection) {
			$api = ConnectionConnector::loadAPI($connection->connection->provider, $connection);
			if ( ! $connection->external_id) {
				try {
					$log = '';
					$log .= 'Updating connection external_id, id: '. $connection->id .' provider: '. $connection->connection->provider .' result: ';
					$externalId = $api->getExternalId();
					$connection->external_id = $externalId;
					$connection->updateUniques();
					if ($externalId) {
						$log .= 'success: '. $externalId;
					} else {
						$log .= 'failed, no id found';
					}
				} catch (\Exception $e) {
					$log .= 'failed, exception thrown: '. $e->getMessage();
				}
				$logs[] = $log;
			}
		}
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('account_connections', function ($table) {
			$table->dropColumn('external_id');
		});
	}

}
