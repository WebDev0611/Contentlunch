<?php

use Illuminate\Database\Schema\Blueprint;
//use Illuminate\Database\Migrations\Migration;
use Launch\Migration;

class PermissionsAddCreateEditContent extends Migration {

  protected $permissions = [
    ['create_edit_content_as_collaborator', 'Edit another user\'s content as a collaborator', 
      ['site_admin', 'manager', 'creator', 'editor']]
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
