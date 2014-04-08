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
    $subscription = Subscription::where('subscription_level', 3)->first();
    $sub = new AccountSubscription;
    $sub->account_id = $account->id;
    $sub->subscription_level = 3;
    $sub->licenses = $subscription->licenses;
    $sub->monthly_price = $subscription->monthly_price;
    $sub->annual_discount = $subscription->annual_discount;
    $sub->training = $subscription->training;
    $sub->features = $subscription->features;
    $sub->save();

    // Create some account specific roles
    $role = new Role;
    $role->name = 'manager';
    $role->display_name = 'Manager / Director';
    $role->global = false;
    $role->account_id = $account->id;
    $role->save();

    $role = new Role;
    $role->name = 'creator';
    $role->display_name = 'Creator / Author';
    $role->global = false;
    $role->account_id = $account->id;
    $role->save();

    $role = new Role;
    $role->name = 'client';
    $role->display_name = 'Client';
    $role->global = false;
    $role->account_id = $account->id;
    $role->save();

    $role = new Role;
    $role->name = 'editor';
    $role->display_name = 'Editor';
    $role->global = false;
    $role->account_id = $account->id;
    $role->save();

	}

}
