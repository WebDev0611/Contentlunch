<?php

class AccountSeeder extends Seeder {

	public function run()
	{
		$now = date('Y-m-d H:i:s');
		$account = new Account;
		$account->title = 'Surge';
		$account->active = true;
    $account->name = 'Surge';
    $account->address = '11820 Northup Way';
    $account->address_2 = 'Suite E-200';
    $account->city = 'Bellevue';
    $account->state = 'WA';
    $account->phone = '866-991-6883';
    $account->subscription = 1;
		$account->save();
	}

}
