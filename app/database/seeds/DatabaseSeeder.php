<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

		// Seeding "refreshes" all the data,
		// so delete data first starting with relation tables
    DB::table('account_user')->delete();
    DB::table('assigned_roles')->delete();
    DB::table('accounts')->delete();
    DB::table('roles')->delete();
    DB::table('users')->delete();

    $this->call('AccountSeeder');
		$this->call('RoleSeeder');

		// User seeder depends on other seeds (roles, accounts), so should run last
		$this->call('UserSeeder');
	}

}
