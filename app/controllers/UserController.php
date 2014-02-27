<?php

class UserController extends BaseController {

	public function index()
	{
		return User::all();
	}

	/**
	 * Store a new user
	 */
	public function store()
	{
		$user = new User;

    //$user->username = Input::get('email');
    $user->username = $user->email = Input::get('email');
    $user->password = Input::get('password');
    $user->first_name = Input::get('first_name');
    $user->last_name = Input::get('last_name');
    $user->confirmed = 1;
    // Taken from ConfideUser. Ardent purges this field
    $user->confirmation_code = md5( uniqid(mt_rand(), true) );

    // The password confirmation will be removed from model
    // before saving. This field will be used in Ardent's
    // auto validation.
    $user->password_confirmation = Input::get( 'password_confirmation' );

    // Save if valid. Password field will be hashed before save
    $user->save();

    if ( $user->id )
    {
    	return Response::json($user->toArray(), 200);
        // Redirect with success message, You may replace "Lang::get(..." for your custom message.
        return Redirect::action('UserController@login')
            ->with( 'notice', Lang::get('confide::confide.alerts.account_created') );
    }
    else
    {
        // Get validation errors (see Ardent package)
        $error = $user->errors()->all(':message');

        return Response::json(array('error' => $error, 401));

        return Redirect::action('UserController@create')
            ->withInput(Input::except('password'))
            ->with( 'error', $error );
    }
	}

	public function show($id)
	{
		$user = User::find($id);
		if ($user) {
			return Response::json($user->toArray());
		}
		return Response::json(array('flash' => 'User not found'));
	}

	public function update($id)
	{
		$user = User::find($id);

		if ($user->updateUniques())
		{
			return Response::json($user->toArray(), 200);
		}
		return $user->errors();
	}

	public function destroy($id)
	{
		$user = User::find($id);
		if ($user->delete()) {
			return Response::json(array('success' => 'OK'), 200);
		}
		return Response::json(array('message' => "Couldn't delete user"), 401);
	}

}