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
    // and seeds test data.
    // so delete data first starting with relation tables
    DB::table('content_tags')->delete();
    DB::table('content_comments')->delete();
    DB::table('content_related')->delete();
    DB::table('content_collaborators')->delete();
    DB::table('content_account_connections')->delete();
    DB::table('content')->delete();

    DB::table('campaign_collaborators')->delete();
    DB::table('campaign_tags')->delete();
    DB::table('campaigns')->delete();


    DB::table('account_user')->delete();

    // Don't delete super user's role
    $superUserID = User::where('username', 'admin@test.com')->pluck('id');
    $adminRoleID = Role::where('name', 'global_admin')->pluck('id');
    DB::table('assigned_roles')->where('user_id', '<>', $superUserID)->delete();

    DB::table('account_content_settings')->delete();
    DB::table('account_connections')->delete();
    DB::table('account_module')->delete();
    DB::table('account_subscription')->delete();
    
    // Don't delete permissions for builtin roles
    $roleIDs = Role::whereNull('account_id')->lists('id');
    DB::table('permission_role')->whereNotIn('role_id', $roleIDs)->delete();
    
    DB::table('accounts')->delete();
    
    // Don't delete intial roles
    DB::table('roles')->whereNotNull('account_id')->delete();
    
    // Don't delete global admin
    DB::table('users')->where('username', '<>', 'admin@test.com')->delete();


    $this->call('AccountSeeder');
		$this->call('UserSeeder');

    // Login as siteadmin, so calls to Confide::user() work
    $user = User::where('username', 'siteadmin@test.com')->first();
    Auth::login($user);

    $this->call('CampaignSeeder');
    $this->call('ContentSeeder');
	}

}
