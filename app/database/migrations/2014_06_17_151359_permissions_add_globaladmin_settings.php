<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PermissionsAddGlobaladminSettings extends Migration {
 protected $permissions = [
    'settings_execute_users',
    'settings_view_profiles',
    'settings_edit_profiles', 
    'settings_edit_account_settings',
    'settings_view_profiles',
    'settings_execute_users',
    'settings_edit_profiles'
  ];

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    foreach ($this->permissions as $permission) {
      $p = Permission::where('name', $permission)->first();
      $role = Role::where('name', 'global_admin')->first();
      $role->perms()->attach($p->id);
    }
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    foreach ($this->permissions as $permission) {
      $p = Permission::where('name', $permission)->first();
      $role = Role::where('name', 'global_admin')->first();
      $role->perms()->detach($p->id);
    }
  }
}
