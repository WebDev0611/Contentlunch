<?php

class UserSeeder extends Seeder {

  public function run()
  {
    $now = date('Y-m-d H:i:s');
    $user = new User;
    $user->id = 1;
    $user->username = 'admin';
    $user->email = 'jkuchynka@surgeforward.com';
    $user->password = $user->password_confirmation = 'launch123';
    $user->created_at = $user->updated_at = $now;
    $user->first_name = 'Site';
    $user->last_name = 'Admin';
    $user->confirmed = 1;
    $user->updateUniques();
  }

}
