<?php

class UserSeeder extends Seeder {

  public function run()
  {
    // Create users
    $users = array(
      'jkuchynka+admin@surgeforward.com' => array('Admin'),
      'jkuchynka+siteadmin@surgeforward.com' => array('Site Admin'),
      'jkuchynka+contentcreator@surgeforward.com' => array('Content Creator'),
      'jkuchynka+manager@surgeforward.com' => array('Manager'),
      'jkuchynka+director@surgeforward.com' => array('Director'),
      'jkuchynka+clevel@surgeforward.com' => array('C-Level'),
      'jkuchynka+editor@surgeforward.com' => array('Editor'),
      'jkuchynka+client@surgeforward.com' => array('Client'),
    );
    $now = date('Y-m-d H:i:s');
    $account = Account::where('title', '=', 'Surge')->first();
    foreach ($users as $email => $roles) {
      $user = new User;
      $user->username = $user->email = $email;
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
