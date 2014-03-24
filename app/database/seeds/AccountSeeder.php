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
		$account->save();

    $sub = new AccountSubscription;
    $sub->account_id = $account->id;
    $sub->auto_renew = true;
    $sub->expiration_date = $expiration;
    $sub->licenses = 20;
    $sub->payment_type = 'CC';
    $sub->subscription_id = 1;  
    $sub->token = 12345;
    $sub->yearly_payment = false;
    $sub->save();

	}

}
