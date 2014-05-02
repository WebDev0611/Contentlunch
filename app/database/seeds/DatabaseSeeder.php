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
                DB::table('content')->delete();
                DB::table('content_tags')->delete();
                DB::table('content_comments')->delete();
                DB::table('content_related')->delete();

                DB::table('campaign_tags')->delete();
                DB::table('campaigns')->delete();


                DB::table('account_user')->delete();
                DB::table('assigned_roles')->delete();
                DB::table('account_content_settings')->delete();
                DB::table('account_connections')->delete();
                DB::table('account_module')->delete();
                DB::table('account_subscription')->delete();
                DB::table('subscriptions')->delete();
                DB::table('permission_role')->delete();
                DB::table('modules')->delete();
                DB::table('permissions')->delete();
                DB::table('accounts')->delete();
                DB::table('roles')->delete();
                DB::table('users')->delete();

                $this->call('ModuleSeeder');
                $this->call('SubscriptionSeeder');
                $this->call('RoleSeeder');
                $this->call('PermissionSeeder');
                $this->call('AccountSeeder');

		// User seeder depends on other seeds (roles, accounts), so should run last
		$this->call('UserSeeder');

                $this->call('CampaignSeeder');
                $this->call('ContentSeeder');
	}

}
