<?php

class AccountSeeder extends Seeder {

	public function run()
	{
		$now = date('Y-m-d H:i:s');
		$account = new Account;
		$account->title = 'Surge';
		$account->active = true;
		$account->save();
	}

}