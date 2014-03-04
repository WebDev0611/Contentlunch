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
		$query = User::with('roles');
		//$query = User::roles();
		if (Input::get('account_id')) {
			//$query->account(Input::get('account_id'));
			//$query->accounts()->where('account_user.account_id', Input::get('account_id'));
		}
		$users = $query->get()->toArray();
		foreach ($users as &$user) {
			if ($user['roles']) {
				$roles = array();
				foreach ($user['roles'] as $role) {
					$roles[$role['id']] = $role['name'];
				}
				$user['roles'] = $roles;
			}
		}
		return Response::json($users, 200);
	}

	/**
	 * Store a new user
	 * return object
	 *   json response of a user
	 */
	public function store()
	{
		$user = new User;

    $user->username = Input::get('email');
    // Taken from ConfideUser. Ardent purges this field
    $user->confirmation_code = md5( uniqid(mt_rand(), true) );

    // The password confirmation will be removed from model
    // before saving. This field will be used in Ardent's
    // auto validation.
    $user->password_confirmation = Input::get('password_confirmation');

    // Attempt to save. Password field will be hashed before save
    if ( $user->save() )
    {
    	// Save roles
    	$user->saveRoles(Input::get('roles'));
    	$return = $user->toArray();
    	$return['roles'] = $user->getRoles();
    	return Response::json($return, 200);
    }
    else
    {
        // Get validation errors (see Ardent package)
        $error = $user->errors()->all(':message');

        return Response::json(array('error' => $error), 401);

        return Redirect::action('UserController@create')
            ->withInput(Input::except('password'))
            ->with( 'error', $error );
    }
	}

	public function show($id)
	{
		$user = User::find($id);
		$return = $user->toArray();
		$return['roles'] = $user->getRoles();
		if ($user) {
			return Response::json($return);
		}
		return Response::json(array('flash' => 'User not found'));
	}

	public function update($id)
	{
		$user = User::find($id);
		
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
			$ret = $user->toArray();
			$ret['roles'] = $user->getRoles();
			return Response::json($ret, 200);
		}
		return Response::json(array(
			'message' => "Couldn't update user",
			'errors' => $user->errors()
			), 401);
	}

	public function destroy($id)
	{
		$user = User::find($id);
		if ($user->delete()) {
			return Response::json(array('success' => 'OK'), 200);
		}
		// 	App::abort(401);
		return Response::json(array('message' => "Couldn't delete user"), 401);
	}

}