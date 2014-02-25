<?php

class UserSeeder extends Seeder {

  public function run()
  {
    // Create users
    $users = array(
      'admin' => array('Admin'),
      'siteadmin' => array('Site Admin'),
      'contentcreator' => array('Content Creator'),
      'manager' => array('Manager'),
      'director' => array('Director'),
      'clevel' => array('C-Level'),
      'editor' => array('Editor'),
      'client' => array('Client'),
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
      $user->updateUniques();
      // Add user's roles
      $role = Role::find_by_name($roles[0]);
      $user->attachRole($role);
      // Add user to surge test account, unless global admin
      if ($roles[0] != 'Admin') {
        $user->accounts()->attach($account);
      }
    }
  }

}
