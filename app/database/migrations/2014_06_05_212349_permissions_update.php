<?php

use Illuminate\Database\Schema\Blueprint;
//use Illuminate\Database\Migrations\Migration;
use Launch\Migration;

class PermissionsUpdate extends Migration {

  protected $permissions = [
    ['content_delete', 'Delete Content', 
      ['site_admin', 'manager']],
    ['settings_edit_account_settings', 'Edit Account Settings',
      ['site_admin']],
    ['settings_view_content_settings', 'Edit Content Settings',
      ['site_admin']],
    ['settings_edit_content_settings', 'Edit Content Settings',
      ['site_admin']],
    ['settings_edit_seo_settings', 'Edit SEO Settings',
      ['site_admin']],
    ['settings_execute_users', 'Edit Users', ['global_admin']],
    ['settings_view_profiles', 'View Profiles', ['global_admin']],
    ['settings_edit_profiles', 'Edit Profiles', ['global_admin']]
  ];

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$this->upPermissions();

    // Remove no longer needed permissions
    foreach (['settings_view_personas', 'settings_edit_personas'] as $name) {
      $id = DB::table('permissions')->where('name', $name)->pluck('id');
      DB::table('permission_role')->where('permission_id', $id)->delete();
      DB::table('permissions')->where('id', $id)->delete();
    }
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
