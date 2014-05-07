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
    DB::table('content')->delete();

    DB::table('campaign_tags')->delete();
    DB::table('campaigns')->delete();


    DB::table('account_user')->delete();
    DB::table('assigned_roles')->delete();
    DB::table('account_content_settings')->delete();
    DB::table('account_connections')->delete();
    DB::table('account_module')->delete();
    DB::table('account_subscription')->delete();
    
    DB::table('permission_role')->delete();
    
    DB::table('accounts')->delete();
    
    // Don't delete intial roles
    DB::table('roles')->whereNotNull('account_id')->delete();
    
    // Don't delete global admin
    DB::table('users')->where('username', '<>', 'admin@test.com')->delete();


    $this->call('AccountSeeder');
		$this->call('UserSeeder');
    $this->call('CampaignSeeder');
    $this->call('ContentSeeder');
	}

}
