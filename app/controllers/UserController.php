<?php

class UserController extends BaseController {

	/**
	 * Get a listing of users
	 * @return array
	 *   json response of users
	 */
	public function index()
	{
		$return = array();
		$query = User::with('roles')
			->with('image')
			->with('accounts');

    if (Input::has('permission')) {
      // User must have ALL passed permissions
      $query->whereHas('roles', function ($q) {
        $perms = explode(',', Input::get('permission'));
        foreach ($perms as $p) {
          $q->whereHas('perms', function ($q) use ($p) {
            $q->where('permissions.name', trim($p));
          });
        }
      }); 
    }

		if (Input::get('roles')) {
			$query->roles(Input::get('roles'));
		}
    return $query->get();

		$users = $query->get()->toArray();
		// @todo: How to limit columns returned with eloquent relationships?
		foreach ($users as &$user) {
			if ($user['roles']) {
				$roles = array();
				foreach ($user['roles'] as $role) {
					$roles[] = array(
						'id' => $role['id'],
						'name' => $role['name']
					);
				}
				$user['roles'] = $roles;
			}
			if ($user['accounts']) {
				$accounts = array();
				foreach ($user['accounts'] as $account) {
					$accounts[] = array(
						'id' => $account['id'],
						'name' => $account['name']
					);
				}
				$user['accounts'] = $accounts;
			}
		}
    /*
    $queries = DB::getQueryLog();
    $last_query = end($queries);
    print_r($queries);
    // */
		return $users;
	}

	/**
	 * Store a new user
	 * return object
	 *   json response of a user
	 */
	public function store()
	{
		// Restrict to create_new_user permission
		if ( ! $this->hasPermission('settings_execute_users') && ! $this->hasPermission('adminster_contentlaunch')) {
			return $this->responseAccessDenied();
		}
		$user = new User;
    $user->username = Input::get('email');
    // Taken from ConfideUser. Ardent purges this field
    $user->confirmation_code = md5( uniqid(mt_rand(), true) );
    // Password can't be null, set a random temp password
    $user->password = $user->password_confirmation = substr(uniqid(mt_rand(), true), 0, 8);
    // Make sure user is unconfirmed, inactive
    // If status or confirmed exist in the request, ardent will try to hydrate from
    // those values, so just set those values in the request input
    $input = Request::all();
    $input['confirmed'] = 0;
    $input['status'] = 0;
    Request::replace($input);
    if ( $user->save() )
    {
    	// Save roles
    	$user->saveRoles(Input::get('roles'));
    	$data = array('user' => $user);
    	// Send confirmation email
    	Mail::send('emails.auth.confirm', $data, function ($message) use ($user) {
				$message->to($user->email)->subject('Account Confirmation');
			});
    	return $this->show($user->id);
    }
    else
    {
      return $this->responseError($user->errors()->all(':message'));
    }
	}

	public function show($id)
	{
		$user = User::with('image')
			->with('roles')
			->with('accounts')
			->find($id);
		if ( ! $user) {
			return $this->responseError("User not found.");
		}
    $user->modules = [];
		if (isset($user->accounts[0])) {
			$account = Account::find($user->accounts[0]->id)->first();
			$modules = $account->modules;
			$modules = $modules->toArray();
			foreach ($modules as &$module) {
				$module['subscribable'] = true;
			}
			$modules[] = ['name' => 'settings', 'title' => 'Settings', 'subscribable' => false];
			$user->modules = $modules;
		} else {
			$user->modules = [];
		}
    
		if (isset($user->roles[0])) {
			$role = Role::find($user->roles[0]->id);
			// Site admin has all permissions
			if ($role->name == 'site_admin') {
				$user->permissions = Permission::all()->toArray();
      } elseif ($role->name == 'global_admin') {
        //$user->permissions = $role->perms->toArray();
        $user->permissions = Permission::all()->toArray();
        $modules = [['name' => 'admin', 'title' => 'Admin', 'subscribable' => false], ['name' => 'settings', 'title' => 'Settings', 'subscribable' => false]];
        $user->modules = $modules;
			} else {
				$user->permissions = $role->perms->toArray();
			}
		}

    if ( ! empty($user->preferences)) {
      $user->preferences = unserialize($user->preferences);
    }

		if ($user) {
			if (Session::get('impersonate_from') && Session::get('impersonate_from') != $id) {
				$user->impersonating = true;
			}
			return $user;
		}
	}

	public function update($id)
	{
		// Restrict to execute users permission
		// @todo: restrict user is in same account
    // Check if user is editing themself
    $loggedInUser = Confide::user();
    if ($loggedInUser && $loggedInUser->id == $id) {

    } else {
		  if ( ! $this->hasAbility(array(), array('settings_execute_users'))) {
			 return $this->responseAccessDenied();
		  }
    }
		$user = User::find($id);

		if ( ! $user) {
			return $this->responseError("User not found.");
		}

		if (Input::get('email')) {
			$user->username = Input::get('email');
		}
		if (Input::get('password')) {
			$user->password = Input::get('password');
			$user->password_confirmation = Input::get('password_confirmation');
		}

		try {
			$updated = $user->updateUniques();
		} catch (Exception $e) {
			$updated = false;
		}

		if ($updated)
		{
			// Attach any roles
			if (Input::get('roles')) {
				$user->saveRoles(Input::get('roles'));
			}
			return $this->show($user->id);
		}
		return $this->responseError($user->errors()->all(':message'));
	}

	public function destroy($id)
	{
		// Restrict to user execute permission
		// @todo: restrict user is in same account
		if ( ! $this->hasAbility(array(), array('settings_execute_users'))) {
			return $this->responseAccessDenied();
		}
		$user = User::find($id);
		if ($user->delete()) {
			return array('success' => 'OK');
		}
		return $this->responseError("Couldn't delete user");
	}

	public function postProfileImage($id)
	{
		$user = User::find($id);
		$file = Input::file('file');
		$upload = new Upload;
		try {
			$upload->process($file);
		} catch (Exception $e) {
			Log::error($e);
			return Response::json(array(
				'message' => "Couldn't upload file",
				'errors' => $e->getMessage()
			), 400);
		}
		if ($upload->id) {
			// Upload succeeded, attach it to user

			// @todo: Figure out why this errors and fix it
			/*
			$upload->user_id = $id;
			$upload->save();
			// */

			$user->image = $upload->id;
			$user->updateUniques();
			return $this->show($user->id);
		}
		return Response::json(array(
			'message' => "Couldn't upload file",
			'errors' => "Error"
		), 400);
	}

  public function savePreferences($userID, $key)
  {
    $user = User::find($userID);
    $currentUser = Confide::user();
    if ( ! $currentUser || ( $userID != $currentUser->id )) {
      return $this->responseAccessDenied();
    }
    $prefs = [];
    if ( ! empty($user->preferences)) {
      $prefs = unserialize($user->preferences);
    }
    if (empty($prefs[$key])) {
      $prefs[$key] = [];
    }
    $prefs[$key] = (array) Input::get('preferences');
    // If these preferences are all empty, remove this key
    $unset = true;
    foreach ($prefs[$key] as $prefKey => $value) {
      if ( ! empty($value)) {
        $unset = false;
      }
    }
    if ($unset) {
      unset($prefs[$key]);
    }
    $user->preferences = serialize($prefs);
    $user->updateUniques();
    return ['success' => 'OK'];
  }

}
