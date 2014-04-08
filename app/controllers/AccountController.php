<?php

class AccountController extends BaseController {

	public function index()
	{
		$accounts = Account::countusers()
			->with('accountSubscription')
			->with('modules')
			->get();
		foreach ($accounts as $account) {
			if ($account->token) {
				$account->hasToken = true;
			} else {
				$account->hasToken = false;
			}
			unset($account->token);
			$account->payment_info = unserialize($account->payment_info);
		}
		return $accounts;
	}

	public function store()
	{
		$account = new Account;
		if (Input::has('payment_info')) {
			$account->payment_info = serialize(Input::get('payment_info'));
		}
		if ($account->save())
		{
			$user = $this->createSiteAdminUser($account);
    	// Send account creation email
    	$this->resend_creation_email($account->id);
			return $this->show($account->id);
		}
		return $this->responseError($account->errors()->all(':message'));
	}

	public function show($id)
	{
		$account = Account::countusers()
			->with('accountSubscription')
			->with('modules')
			->where('accounts.id', $id)
			->first();
		if ($account->token) {
			$account->hasToken = true;
		} else {
			$account->hasToken = false;
		}
		unset($account->token);
		$account->payment_info = unserialize($account->payment_info);
		return $account;
	}

	public function update($id)
	{
		$account = Account::find($id);
		if (Input::has('payment_info')) {
			$account->payment_info = serialize(Input::get('payment_info'));
		}
		if ($account->updateUniques())
		{
			return $this->show($account->id);
		}
		return $this->responseError($account->errors()->all(':message'));
	}

	public function destroy($id)
	{
		$account = Account::find($id);
		if ($account && $account->delete()) {
			return Response::json(array('success' => 'OK'), 200);
		}
		return $this->responseError("Couldn't delete account");
	}

	public function resend_creation_email($id)
	{
		$account = Account::find($id);
		$user = User::where('email', $account->email)->first();
		$token = $user->confirmation_code;
		$data = array(
			'account' => $account,
			'user' => $user,
			'token' => $token
		);
		Mail::send('emails.account.creation', $data, function ($message) use ($account) {
			$message->to($account->email)->subject('Account Created');
		});
		return array('success' => 'OK');
	}

	public function request_update_email($id)
	{
		$account = Account::find($id);
		$data = array(
			'account' => $account,
			'send_date' => date('M/d/Y'),
			'send_time' => date('h:i:s'),
		);
		Mail::send('emails.account.request_update', $data, function ($message) use ($account) {
			$message->to('jkuchynka@surgeforward.com')->subject('Account Update Request - '. $account->name);
		});
	}

	protected function createSiteAdminUser($account)
	{
		// When creating a new account, an email must be attached
		// to the account record.
		// This email should be turned into a user account that will
		// become the account's site admin.
		$user = User::where('email', $account->email)->first();
		if ( ! $user) {
			$user = new User;
			$user->username = $account->email;
			$user->email = $account->email;
			$user->confirmation_code = md5( uniqid(mt_rand(), true) );
			$user->password = $user->password_confirmation = substr(uniqid(mt_rand(), true), 0, 8);
			$user->confirmed = 0;
			$user->status = 0;
			$user->save();
		}
		// Attach user to account
		$user->accounts()->attach($account);
		// Attach site admin role
  	$role = Role::find_by_name('Site Admin');
  	$user->attachRole($role);
  	return $user;
	}

}
