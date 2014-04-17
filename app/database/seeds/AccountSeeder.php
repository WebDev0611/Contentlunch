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
        $settings->include_name = array('audio' => 1, 'ebook' => 1,
            'google_drive' => 1, 'photo' => 1, 'video' => 1);
        $settings->allow_edit_date = array('blog_post' => 1, 'email' => 1,
            'landing_page' => 1, 'twitter' => 1, 'whitepaper' => 1);
        $settings->keyword_tags = array('case_study' => 1, 'facebook_post' => 1,
            'linkedin' => 1, 'salesforce_asset' => 1);
        $settings->publishing_guidelines = 'Publishing guidelines here';
        $settings->personas = array(
            array(
                'name' => 'CMO',
                'suspects' => 'Description of how a CMO acts at the Suspect Stage Description of how a CMO acts at the Suspect Stage',
                'prospects' => 'Description of how a CMO acts at the Prospects Stage Description of how a CMO acts at the Prospects Stage',
                'lead' => 'Description of how a CMO acts at the Lead Stage Description of how a CMO acts at the Lead Stage',
                'opportunities' => 'Description of how a CMO acts at the Opportunities Stage Description of how a CMO acts at the Opportunities Stage',
            ),
            array(
                'name' => 'VP Sales',
                'suspects' => 'Description of how a VP Sales acts at the Suspect Stage Description of how a VP Sales acts at the Suspect Stage',
                'prospects' => 'Description of how a VP Sales acts at the Prospects Stage Description of how a VP Sales acts at the Prospects Stage',
                'lead' => 'Description of how a VP Sales acts at the Lead Stage Description of how a VP Sales acts at the Lead Stage',
                'opportunities' => 'Description of how a VP Sales acts at the Opportunities Stage Description of how a VP Sales acts at the Opportunities Stage',
            ),
            array(
                'name' => 'Sales Rep',
                'suspects' => 'Description of how a Sales Rep acts at the Suspect Stage Description of how a Sales Rep acts at the Suspect Stage',
                'prospects' => 'Description of how a Sales Rep acts at the Prospects Stage Description of how a Sales Rep acts at the Prospects Stage',
                'lead' => 'Description of how a Sales Rep acts at the Lead Stage Description of how a Sales Rep acts at the Lead Stage',
                'opportunities' => 'Description of how a Sales Rep acts at the Opportunities Stage Description of how a Sales Rep acts at the Opportunities Stage',
            ),
            array(
                'name' => 'Product Manager',
                'suspects' => 'Description of how a Product Manager acts at the Suspect Stage Description of how a Product Manager acts at the Suspect Stage',
                'prospects' => 'Description of how a Product Manager acts at the Prospects Stage Description of how a Product Manager acts at the Prospects Stage',
                'lead' => 'Description of how a Product Manager acts at the Lead Stage Description of how a Product Manager acts at the Lead Stage',
                'opportunities' => 'Description of how a Product Manager acts at the Opportunities Stage Description of how a Product Manager acts at the Opportunities Stage'
            )
        );
        $settings->save();
	}

}
