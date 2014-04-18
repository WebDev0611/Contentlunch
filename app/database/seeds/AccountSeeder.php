<?php

class AccountSeeder extends Seeder {

	public function run()
	{
		$now = date('Y-m-d H:i:s');
        $expiration = date('Y-m-d H:i:s', strtotime('+30 days'));
		$account = new Account;
		$account->title = 'Surge';
		$account->active = true;
        $account->name = 'Surge';
        $account->address = '11820 Northup Way';
        $account->address_2 = 'Suite E-200';
        $account->city = 'Bellevue';
        $account->state = 'WA';
        $account->phone = '866-991-6883';
        $account->country = 'US';
        $account->zipcode = '98005';
        $account->email = 'info@surgeforward.com';
        $account->auto_renew = true;
        $account->expiration_date = $expiration;
        $account->payment_type = 'CC';
        $account->token = 12345;
        $account->yearly_payment = false;
		$account->save();

        // Give access to all modules
        $modules = Module::all();
        foreach ($modules as $module) {
          $account->modules()->save($module);
        }

        // Save account's subscription
        $subscription = Subscription::where('subscription_level', 1)->first();
        $sub = new AccountSubscription;
        $sub->account_id = $account->id;
        $sub->subscription_level = 3;
        $sub->licenses = $subscription->licenses;
        $sub->monthly_price = $subscription->monthly_price;
        $sub->annual_discount = $subscription->annual_discount;
        $sub->training = $subscription->training;
        $sub->features = $subscription->features;
        $sub->save();

        // Attach builtin roles
        $roles = Role::where('builtin', 1)->where('account_id', NULL)->get();
        foreach ($roles as $bRole) {
            $role = new AccountRole;
            $role->account_id = $account->id;
            $role->name = $bRole->name;
            $role->display_name = $bRole->display_name;
            $role->status = 1;
            $role->global = 0;
            $role->builtin = 1;
            $role->deletable = 0;
            $role->save();
            // Copy default role permissions to newly created role
            $perms = $bRole->perms()->get();
            if ($perms) {
                $attach = array();
                foreach ($perms as $perm) {
                    $attach[] = $perm->id;
                }
                $role->perms()->sync($attach);
            }
        }

        // Custom role
        $role = new AccountRole;
        $role->account_id = $account->id;
        $role->name = 'custom';
        $role->display_name = 'Custom';
        $role->status = 1;
        $role->global = 0;
        $role->builtin = 0;
        $role->deletable = 1;
        $role->save();

        // Save content settings
        $settings = new AccountContentSettings;
        $settings->account_id = $account->id;
        $settings->include_name = array(
            'enabled' => 1,
            'content_types' => array(
                'audio', 'ebook', 'google_drive', 'photo', 'video'
            )
        );
        $settings->allow_edit_date = array(
            'enabled' => 1,
            'content_types' => array(
                'blog_post', 'email', 'landing_page', 'twitter', 'whitepaper'
            )
        );
        $settings->keyword_tags = array(
            'enabled' => 1,
            'content_types' => array(
                'case_study', 'facebook_post', 'linkedin', 'salesforce_asset'
            )
        );
        $settings->publishing_guidelines = 'Publishing guidelines here';
        $settings->persona_columns = array('suspects', 'prospects', 'leads', 'opportunities', 'custom');
        $settings->personas = array(
            array(
                'name' => 'CMO',
                'columns' => array(
                    'Description of how a CMO acts at the Suspect Stage Description of how a CMO acts at the Suspect Stage',
                    'Description of how a CMO acts at the Prospects Stage Description of how a CMO acts at the Prospects Stage',
                    'Description of how a CMO acts at the Lead Stage Description of how a CMO acts at the Lead Stage',
                    'Description of how a CMO acts at the Opportunities Stage Description of how a CMO acts at the Opportunities Stage',
                    'Custom description'
                )
            ),
            array(
                'name' => 'VP Sales',
                'columns' => array(
                    'Description of how a VP Sales acts at the Suspect Stage Description of how a VP Sales acts at the Suspect Stage',
                    'Description of how a VP Sales acts at the Prospects Stage Description of how a VP Sales acts at the Prospects Stage',
                    'Description of how a VP Sales acts at the Lead Stage Description of how a VP Sales acts at the Lead Stage',
                    'Description of how a VP Sales acts at the Opportunities Stage Description of how a VP Sales acts at the Opportunities Stage',
                    'Custom description'
                )
            ),
            array(
                'name' => 'Sales Rep',
                'columns' => array(
                    'Description of how a Sales Rep acts at the Suspect Stage Description of how a Sales Rep acts at the Suspect Stage',
                    'Description of how a Sales Rep acts at the Prospects Stage Description of how a Sales Rep acts at the Prospects Stage',
                    'Description of how a Sales Rep acts at the Lead Stage Description of how a Sales Rep acts at the Lead Stage',
                    'Description of how a Sales Rep acts at the Opportunities Stage Description of how a Sales Rep acts at the Opportunities Stage',
                    'Custom description'
                )
            ),
            array(
                'name' => 'Product Manager',
                'columns' => array(
                    'Description of how a Product Manager acts at the Suspect Stage Description of how a Product Manager acts at the Suspect Stage',
                    'Description of how a Product Manager acts at the Prospects Stage Description of how a Product Manager acts at the Prospects Stage',
                    'Description of how a Product Manager acts at the Lead Stage Description of how a Product Manager acts at the Lead Stage',
                    'Description of how a Product Manager acts at the Opportunities Stage Description of how a Product Manager acts at the Opportunities Stage',
                    'Custom description'
                )
            )
        );
        $settings->save();

        // Save SEO connections
        $connection = new AccountConnection;
        $connection->account_id = $account->id;
        $connection->type = 'seo';
        $connection->name = 'SEO Ultimate';
        $connection->status = 1;
        $connection->settings = array(
            'apikey' => '123asdf',
            'url' => 'http://seoultimate.com'
        );
        $connection->save();
        $connection = new AccountConnection;
        $connection->account_id = $account->id;
        $connection->type = 'seo';
        $connection->name = 'Sales Machine';
        $connection->status = 1;
        $connection->settings = array(
            'apikey' => '123asdfasd',
            'url' => 'http://seomachine.com'
        );
        $connection->save();

        // Save content connections
        $connection = new AccountConnection;
        $connection->account_id = $account->id;
        $connection->type = 'content';
        $connection->name = 'Hubspot';
        $connection->status = 1;
        $connection->settings = array(
            'apikey' => '123asdf',
            'url' => 'http://surge.hubspot.com'
        );
        $connection->save();
        $connection = new AccountConnection;
        $connection->account_id = $account->id;
        $connection->type = 'content';
        $connection->name = 'Linkedin';
        $connection->status = 1;
        $connection->settings = array(
            'apikey' => '123asdf',
            'url' => 'http://linkedin.com/surge'
        );
        $connection->save();
        $connection = new AccountConnection;
        $connection->account_id = $account->id;
        $connection->type = 'content';
        $connection->name = 'Wordpress';
        $connection->status = 1;
        $connection->settings = array(
            'apikey' => '123asdf',
            'url' => 'http://wordpress.com/surge'
        );
        $connection->save();
	}

}
