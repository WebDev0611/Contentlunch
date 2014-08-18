<?php

class AccountController extends BaseController {

	public function index()
	{
		// Restrict to global admins
		if ( ! $this->hasRole('global_admin')) {
			return $this->responseAccessDenied();
		}
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

	public function store($checkAuth = true)
	{
		// Restrict to global admins
		if ($checkAuth && ! $this->hasRole('global_admin')) {
			return $this->responseAccessDenied();
		}
		$account = new Account;
		if (Input::has('payment_info')) {
			$account->payment_info = serialize(Input::get('payment_info'));
		}
		if ($account->save()) {
			// Attach builtin roles, they can't be deleted
			$roles = Role::whereNull('account_id')->where('name', '<>', 'global_admin')->get();
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
			$user = $this->createSiteAdminUser($account);
			// Send account creation email
			$this->resend_creation_email($account->id, $checkAuth);
			return $this->show($account->id, $checkAuth);
		}
		return $this->responseError($account->errors()->all(':message'));
	}

	public function store_beta_signup()
	{
		$account = $this->store(false);
		// may be an error, but won't be if it has an ID
		if (!@$account->id) return $account;
		$user = $account->getSiteAdminUser();
		$account->confirmation_code = $user->confirmation_code;;

		try {
			$sub = App::make('AccountSubscriptionController')->post_subscription($account->id, false);

			if (!@$sub->id) {
				throw new Exception('Error saving subscription');
			}
		} catch (Exception $e) {
			// undo account
			Account::destroy($account->id);
			User::destroy($user->id);
			DB::table('assigned_roles')->where('user_id', $user->id)->delete();
			AccountRole::where('account_id', $account->id)->delete();
			return $sub ?: $this->responseError($e, 401);
		}

		$account->subscription = $sub->toArray();

		return $account;
	}


	public function show($id, $checkAuth = true)
	{
		// Restrict to global admins or user is connected to account
		if ($checkAuth && ! $this->inAccount($id)) {
			return $this->responseAccessDenied();
		}
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
		// Restrict to global admins and site admins
		if ( ! $this->hasAbility(array('global_admin', 'site_admin'))) {
			return $this->responseAccessDenied();
		}
		// Restrict to user connected to account
		if ( ! $this->inAccount($id)) {
			return $this->responseAccessDenied();
		}
		$account = Account::find($id);
		// Check for changing active status. If going from 1 to 0, the account
		// is getting cancelled, and an email will need to be sent
		// Wait until after save
		$send_cancellation_email = false;
		if ($account->active == 1 && Input::has('active') && Input::get('active') == 0) {
			$send_cancellation_email = true;
		}
		if (Input::has('payment_info')) {
			$account->payment_info = serialize(Input::get('payment_info'));
		}
		if ($account->updateUniques())
		{
			if ($send_cancellation_email) {
				$this->send_cancellation_email($account);
			}
			return $this->show($account->id);
		}
		return $this->responseError($account->errors()->all(':message'));
	}

	public function destroy($id)
	{
		// Restrict to global admins and site admins
		if ( ! $this->hasAbility(array('global_admin', 'site_admin'))) {
			return $this->responseAccessDenied();
		}
		// Restrict to user is connected to account
		if ( ! $this->inAccount($id)) {
			return $this->responseAccessDenied();
		}
		$account = Account::find($id);
		if ($account && $account->delete()) {
			return Response::json(array('success' => 'OK'), 200);
		}
		return $this->responseError("Couldn't delete account");
	}

	public function resend_creation_email($id, $checkAuth = true)
	{
		// Restrict to global admins
		if ($checkAuth && ! $this->hasRole('global_admin')) {
			return $this->responseAccessDenied();
		}
		$account = Account::find($id);
		$user = $account->getSiteAdminUser();
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

	public function send_support_email()
	{
		$user = Confide::user();
		if (!$user) {
			// they they aren't even logged in!
			return $this->responseError('Not logged in', 401);
		}

		Mail::send('emails.account.support', Input::get(), function ($message) {
			$message->to('support@contentlaunch.com')->subject('Customer Support');
		});

		return array('success' => 'OK');
	}

	public function request_update_email()
	{
		// Restrict to site admins
		if ( ! $this->hasRole('site_admin')) {
			return $this->responseAccessDenied();
		}
		$data = array(
			'company_name' => Input::get('company'),
			'name' => Input::get('name'),
			'email' => Input::get('email'),
			'phone' => Input::get('phone'),
			'details' => Input::get('details'),
			'send_date' => date('M/d/Y'),
			'send_time' => date('h:i:s'),
		);
		Mail::send('emails.account.request_update', $data, function ($message) {
			$message
				->to('support@contentlaunch.com', 'Launch Support')
				->cc('mmayo@surgeforward.com', 'Mark Mayo')
				->cc('jkuchynka@surgeforward.com', 'Jason Kuchynka')
				->from(Input::get('email'))
				->subject('Account Update Request - '. Input::get('company'));
		});
	}

	public function send_cancellation_email($account)
	{
		// Send cancellation email
		Mail::send('emails.account.cancellation', array(), function ($message) use ($account) {
			$message
				->to($account->email)
				->from('support@contentlaunch.com', 'Launch Support')
				->subject('Content Launch Account Cancellation');
		});
	}

	public function charge_account($id)
	{

		$balancedAccount = new Launch\Balanced($id);
		//$balanced->getCustomerInfo();
		$balancedAccount->getPayment();
		return;

		Httpful\Bootstrap::init();
		RESTful\Bootstrap::init();
		Balanced\Bootstrap::init();
		$account = Account::find($id);
		if ( ! $account->token) {
			return;
		}
		// If no expiration date yet, charge the account
		Balanced\Settings::$api_key = Config::get('app.balanced.api_key_secret');
		$payment_info = unserialize($account->payment_info);
		$balanced_info = unserialize($account->balanced_info);
		// Create a customer if it doesn't already exist
		if ( ! isset($balanced_info['customer_uri'])) {
			$customer = new Balanced\Customer(array(
				'name' => $account->name,
				'email' => $account->email
			));
			$customer->save();
			$balanced_info['customer_uri'] = $customer->href;
			$account->balanced_info = serialize($balanced_info);
			$account->updateUniques();
		} else {
			$customer = Balanced\Customer::get($balanced_info['customer_uri']);
		}
		// Account token is the stored uri for credit card / bank account
		$card = Balanced\Card::get($account->token);
		// Associate to customer if it's not already
		if (empty($card->links->customer)) {
			$card->associateToCustomer($customer);
		}

		$ret = $card->debits->create(array(
			'amount' => 5000,
			'appears_on_statement_as' => 'contentlaunch.com for account '. $account->name,
			'description' => 'Some descriptive text for the debit in the dashboard'
		));

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
    $role = Role::where('name', 'site_admin')->where('account_id', $account->id)->first();
  	$user->attachRole($role);
  	return $user;
	}

}
