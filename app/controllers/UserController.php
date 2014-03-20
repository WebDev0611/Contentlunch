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
		if (Input::get('roles')) {
			$query->roles(Input::get('roles'));
		}
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
		return $users;
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
    	return $this->show($user->id);
    }
    else
    {
        // Get validation errors (see Ardent package)
        $error = $user->errors()->all(':message');

        return Response::json(array('error' => $error), 401);
    }
	}

	public function show($id)
	{
		$user = User::with('image')
			->with('roles')
			->with('accounts')
			->find($id);
		$return = $user->toArray();
		//$return['roles'] = $user->getRoles();
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
			return $this->show($user->id);
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

}
