<?php

class UserSeeder extends Seeder {

  public function run()
  {
    // Create users for the Surge testing account
    $users = [
      [
        'username' => 'siteadmin', 
        'role' => 'site_admin', 
        'first_name' => 'Site', 
        'last_name' => 'Admin', 
        'address' => '123 SW 321', 
        'city' => 'Spokane', 
        'state' => 'WA', 
        'country' => 'US', 
        'phone' => '5091231234', 
        'title' => 'Surge Admin'
      ],
      [
        'username' => 'manager', 
        'role' => 'manager', 
        'first_name' => 'Michael', 
        'last_name' => 'Scott', 
        'address' => '54 N First st', 
        'city' => 'Seattle', 
        'state' => 'WA', 
        'country' => 'US', 
        'phone' => '2061231234', 
        'title' => 'Mr. Manager'
      ],
      [
        'username' => 'creator', 
        'role' => 'creator', 
        'first_name' => 'Pam', 
        'last_name' => 'Beasley', 
        'address' => '999 E Division', 
        'city' => 'San Diego', 
        'state' => 'CA', 
        'country' => 'US', 
        'phone' => '5099911919', 
        'title' => 'Content Creator'
      ],
      [
        'username' => 'editor', 
        'role' => 'editor', 
        'first_name' => 'Jim', 
        'last_name' => 'Halpert', 
        'address' => '51 E Road St.', 
        'city' => 'Las Vegas', 
        'state' => 'NV', 
        'country' => 'US', 
        'phone' => '2034511232', 
        'title' => 'Editor'
      ],
      [
        'username' => 'client', 
        'role' => 'client', 
        'first_name' => 'Dwight', 
        'last_name' => 'Schrute', 
        'address' => '15108 NE River Rd', 
        'city' => 'Scranton', 
        'state' => 'PA', 
        'country' => 'US', 
        'phone' => '1234567890', 
        'title' => 'Client'
      ]
    ];
    $now = date('Y-m-d H:i:s');
    $account = Account::where('title', '=', 'Surge')->first();
    foreach ($users as $row) {
      $user = new User;
      $user->username = $user->email = $row['username'] .'@test.com';
      $user->password = $user->password_confirmation = 'launch123';
      $user->created_at = $user->updated_at = time();
      $user->first_name = $row['first_name'];
      $user->last_name = $row['last_name'];
      $user->address = $row['address'];
      $user->city = $row['city'];
      $user->state = $row['state'];
      $user->country = $row['country'];
      $user->phone = $row['phone'];
      $user->title = $row['title'];
      $user->confirmed = 1;
      $user->status = 1;
      $user->updateUniques();
      // Add user's roles
      $role = Role::where('name', $row['role'])->where('account_id', $account->id)->first();
      $user->attachRole($role);
      // Add user to surge test account
      $user->accounts()->attach($account);
      $this->command->info('Added user to Surge account: '. $user->username);
    }
  }

}
