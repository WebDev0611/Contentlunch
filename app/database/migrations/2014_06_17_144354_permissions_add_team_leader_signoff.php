<?php

use Illuminate\Database\Schema\Blueprint;
//use Illuminate\Database\Migrations\Migration;
use Launch\Migration;

class PermissionsAddTeamLeaderSignoff extends Migration {

  protected $permissions = [
    ['create_execute_team_leader_signoff', 'Team Leader Signoff', 
      ['site_admin', 'manager']],
  ];

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$this->upPermissions();
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		$this->downPermissions();
	}

}
