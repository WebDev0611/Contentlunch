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

	}

}
