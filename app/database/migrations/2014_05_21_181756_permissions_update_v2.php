<?php

use Illuminate\Database\Schema\Blueprint;
//use Illuminate\Database\Migrations\Migration;
use Launch\Migration;

class PermissionsUpdateV2 extends Migration {

  protected $permissions = [
    // Key, Display name, Default roles to assign permissions to
    // Create
    ['create_execute_convert_concept_own', 'Convert own concept to content or campaign',
      ['site_admin', 'manager', 'creator']],
    ['create_execute_convert_concept_other', 'Convert someone else\'s concept to content or campaign',
      ['site_admin', 'manager']],
    ['collaborate_execute_tasks_collaborators', 'Assign tasks to Collaborators',
      ['site_admin', 'manager', 'creator']],
    ['collaborate_execute_tasks_complete', 'Complete tasks assigned to others',
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
