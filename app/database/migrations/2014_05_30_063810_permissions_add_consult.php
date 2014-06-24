<?php

use Illuminate\Database\Schema\Blueprint;
//use Illuminate\Database\Migrations\Migration;
use Launch\Migration;

class PermissionsAddConsult extends Migration {

  protected $permissions = [
    // Key, Display name, Default roles to assign permissions to
    // Consult
    ['consult_view_library', 'Access to Create Library (Files & Folders)',
      ['manager', 'creator']],
    ['consult_edit_library', 'Access to Create Library (Files & Folders)',
      ['manager', 'creator']],
    ['consult_execute_library_new_folder', 'Create a new Folder',
      ['manager', 'creator']],
    ['consult_execute_forum_create', 'Access to Create User Forum',
      ['manager', 'creator']],
    ['consult_execute_video_create', 'Access to Create Video Conference',
      ['manager', 'creator']]
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
