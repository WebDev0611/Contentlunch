<?php

class AccountSeeder extends Seeder {

	public function run()
	{
		DB::table('accounts')->delete();
		$now = date('Y-m-d H:i:s');
		$account = new Account;
		$account->title = 'Surge';
		$account->active = false;
		$account->save();
	}

}