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

	protected function createAccount($autoLogin = false) {
		/** Creates a brand new account
		 *  If $autoLogin, the current user is logged in as that account.
		 */
		$account = new Account;
		$account->expiration_date = \Carbon\Carbon::now()->addDays(30);
//		if (Input::has('payment_info')) {
//			$account->payment_info = serialize(Input::get('payment_info'));
//		}
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
			if($autoLogin) {
				Auth::login($user);
			}
			// Send account creation email
			$this->resend_creation_email($account->id, false);

			App::make('HasOffersController')->store($account->id);

			// Save default personas and settings
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
			$settings->publishing_guidelines = '';
			$settings->persona_columns = array('suspects', 'prospects', 'leads', 'opportunities', 'custom');
			$settings->personas = array(
				array(
					'name' => 'CMO',
					'columns' => array(
						'Concerned with learning more about industry best practices and staying ahead of the curve. Results oriented, needs materials that are shore and sweet with solid, actionable take aways.',
						'Looking for specific information to pass along to VP or Director for actions. Has interacted with informal materials that will give indication of interest. Engage with sof, informational content to help them better identify what their actual problem is and ways to work towards a potential solution.',
						'Has engaged in the buying process and is more concerned with results and big picture messaging than the functionality of product. Targeted messaging with upper level implications is key to continuing down the funnel.',
						'Needs a reason to buy and we must message them according to where they are in the buying stage. This information should continue to be somewhat educational but pushing towards a decision for funnel.',
						'Custom description'
					)
				),
				array(
					'name' => 'VP Sales',
					'columns' => array(
						'Similar to CMO, concerned with bigger picture messages that will be helpful to the overall performance of their team. Prefers content with simple, actionable messaging as well as hard facts, numbers, and graphs/charts.',
						'Looking for specifici information to pass along to Director or Manager for actions. Has interacted with informal materials that will give indication of interest. Engage with soft, informational content to help them better indentify what their actual problem is and ways to work towards a potential solution.',
						'Has engaged in the buying process and is more concerned with results and big picture messaging than the functionality of product. Targeted messaging with upper level implications is key to continuing down the funnel.',
						'Needs a reason to buy and we must message them according to where they are in the buying stage. This information should continue to be somewhat educational but pushing towards a decision for funnel.',
						'Custom description'
					)
				),
				array(
					'name' => 'Sales Rep',
					'columns' => array(
						'Focused on educating themselves with content that will help in their everyday work. Specific techniques and day-to-day actions or tools they can use are preferred over higher level, best practices messaging.',
						'Will be looking for solutions to a specific problem. Materials to help them identify this solution are needed. More educational materials are needed with a slight hint at solutions offered.',
						'Needs information to help them compare our solution to others. Facts driven, but still wants to be educated about both the overall problem, how their competitors are handling it and why we will provide the best in-calss results that are sustainable.',
						'Stronger messaging driving to a purchase through targeted, solution driven content.',
						'Custom description'
					)
				),
				array(
					'name' => 'Product Manager',
					'columns' => array(
						'Interested in understanding industry best practices as they pertain to the specific product and what their competition may be doing. Looking for messaging that will help give them an advantage over competitors along with things that will help them perform better daily.',
						'Will be looking for solutions to a specific problem. Materials to help them identify this solution are needed. More educational materials are needed with a slight hint at solutions offered.',
						'Needs information to help them compare our solution to others. Facts driven, but still wants to be educated about both the overall problem, how their competitors are handling it and why we will provide the best in-calss results that are sustainable.',
						'Stronger messaging driving to a purchase through targeted, solution driven content.',
						'Custom description'
					)
				)
			);
			$settings->save();
			return $account;
		}
		return $account;
	}

	public function register() {
		$account = $this->createAccount(true);
		if(!$account->exists) {
			return $account->errors()->all(':message');
		}

		$sub = App::make('AccountSubscriptionController')->create_subscription($account->id, 3, 25, 0, 0, 1,
															"API, Premium Support, Custom Reporting, Advanced Security",
															"freemium");

		if(! $sub->exists() ) {
			return $sub->errors()->all(':message');
		}

		return $account;
	}

	public function store($checkAuth = true)
	{
		// Restrict to global admins
		if ($checkAuth && ! $this->hasRole('global_admin')) {
			return $this->responseAccessDenied();
		}

		$account = $this->createAccount();

		if($account) {
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
        if(Input::has('token') && Input::get('token') != $account->token) {
            $account->token = Input::get('token');
            $balancedAccount = new Launch\Balanced($account);
            try {
                $balancedAccount->syncPayment();
            }
            catch(Balanced\Errors\Declined $e) {
                return $this->responseError('Your credit card was declined');
            }
            catch(Balanced\Errors\Error $e) {
                return $this->responseError('Something went wrong while processing your card');
            }
        }

        if ($account->token && !$account->payment_date) {
        	$account->payment_date = $account->expiration_date;
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

    public function csv() {
        $query = DB::table('accounts')
            ->join('account_user', 'accounts.id', '=', 'account_user.account_id')
            ->join('users', 'account_user.user_id', '=', 'users.id')
            ->join('assigned_roles', 'users.id', '=', 'assigned_roles.user_id')
            ->join('roles', function($join) {
                $join
                    ->on('assigned_roles.role_id', '=', 'roles.id')
                    ->on('accounts.id', '=', 'roles.account_id');
            })->select(
                'accounts.name',
                'accounts.email',
                'accounts.phone',
                'accounts.created_at',
                'users.email as user_email',
                'users.title',
                'users.first_name',
                'users.last_name'
            )->where('roles.name', '=', 'site_admin');

        $results = $query->get();

        $csv = '';
        $headers = [
            'Account Name',
            'Account Email',
            'Account Phone',
            'Create Date',
            'User Email',
            'Title',
            'First Name',
            'Last Name'
        ];

        //wrap headers in "" and implode with ,
        $csv .= implode(',', array_map(function($header) {return '"'.$header.'"';}, $headers)) . "\n";

        foreach($results as $row) {
            $row = (array) $row;
            $csv .= implode(',', array_map(function($d) {return '"'.$d.'"';}, $row)) . "\n";
        }

        $headers = array(
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="accounts.csv"',
        );

        return Response::make(rtrim($csv, "\n"), 200, $headers);
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
			$message
                ->from('support@contentlaunch.com', 'Content Launch')
                ->to($account->email)
                ->subject('Account Created');
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
			$message
                ->to('support@contentlaunch.com')
                ->cc('mmayo@surgeforward.com')
                ->subject('Customer Support');
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

    function scoreAllAccounts() {
        set_time_limit(0);

        $accounts = Account::all();

        $AccountConnectionsController = App::make('AccountConnectionsController');
        $ContentController = App::make('ContentController');
        $CampaignController = App::make('CampaignController');
        $MeasureController = App::make('MeasureController');

        foreach($accounts as $account) {
            try {
                $AccountConnectionsController->updateStats($account->id);

                $ContentController->updateScores($account->id);
                $CampaignController->updateScores($account->id);

                $MeasureController->updateStats($account->id);
            }
            catch (Exception $e) {
                var_dump($e->getMessage());
                var_dump($e->getTrace());
            }
        }
        echo 'done';
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
			if( Input::has('password')) {
				$user->password = Input::get('password');
				$user->confirmed = 1;
			} else {
				$user->password = $user->password_confirmation = substr(uniqid(mt_rand(), true), 0, 8);
				$user->confirmed = 0;
			}
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
