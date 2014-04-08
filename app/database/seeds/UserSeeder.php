<?php

class UserSeeder extends Seeder {

  public function run()
  {
    // Create users
    $users = array(
      'admin' => array('global_admin'),
      'siteadmin' => array('site_admin'),
      'contentcreator' => array('creator'),
      'manager' => array('manager'),
      'editor' => array('editor'),
      'client' => array('client'),
    );
    $now = date('Y-m-d H:i:s');
    $account = Account::where('title', '=', 'Surge')->first();
    foreach ($users as $name => $roles) {
      $user = new User;
      $user->username = $user->email = $name .'@test.com';
      $user->password = $user->password_confirmation = 'launch123';
      $user->created_at = $user->updated_at = time();
      $user->first_name = 'Test';
      $user->last_name = $roles[0];
      $user->confirmed = 1;
      $user->status = 1;
      $user->updateUniques();
      // Add user's roles
      $role = Role::find_by_name($roles[0]);
      $user->attachRole($role);
      // Add user to surge test account, unless global admin
      if ($roles[0] != 'global_admin') {
        $user->accounts()->attach($account);
      }
    }
  }

}
