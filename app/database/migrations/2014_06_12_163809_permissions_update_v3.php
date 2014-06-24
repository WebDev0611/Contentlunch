<?php

use Illuminate\Database\Schema\Blueprint;
//use Illuminate\Database\Migrations\Migration;
use Launch\Migration;

class PermissionsUpdateV3 extends Migration {

  protected $permissions = [
    ['create_execute_archive_restore_content_own', 'Archive Restore Own Content', 
      ['site_admin', 'manager', 'creator', 'client', 'editor']],
    ['create_execute_archive_restore_content_other', 'Archive Restore Other Content',
      ['site_admin', 'manager']],
    ['collaborate_delete_tasks', 'Collaborate delete tasks',
      ['site_admin', 'manager', 'editor']],
    ['promote_content_own', 'Promote Own Content', ['site_admin', 'manager', 'creator', 'client', 'editor']],
    ['promote_content_other', 'Promote Other Content',
      ['site_admin', 'manager', 'editor']],
    ['promote_campaign_own', 'Promote Own Campaign', ['site_admin', 'manager', 'creator', 'client', 'editor']],
    ['promote_campaign_other', 'Promote Other Campaign', ['site_admin', 'manager', 'editor']]
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
