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

    $user->username = Input::get( 'username' );
    $user->email = Input::get( 'email' );
    $user->password = Input::get( 'password' );
    $user->first_name = Input::get('first_name');
    $user->last_name = Input::get('last_name');

    // The password confirmation will be removed from model
    // before saving. This field will be used in Ardent's
    // auto validation.
    $user->password_confirmation = Input::get( 'password_confirmation' );

    // Save if valid. Password field will be hashed before save
    $user->save();

    if ( $user->id )
    {
        // Redirect with success message, You may replace "Lang::get(..." for your custom message.
        return Redirect::action('UserController@login')
            ->with( 'notice', Lang::get('confide::confide.alerts.account_created') );
    }
    else
    {
        // Get validation errors (see Ardent package)
        $error = $user->errors()->all(':message');

        return Redirect::action('UserController@create')
            ->withInput(Input::except('password'))
            ->with( 'error', $error );
    }
	}

	public function show($id)
	{
		return User::find($id);
	}

	public function update($id)
	{
		$user = User::update($id);
		if ($user->isSaved())
		{
			return $user;
		}
		return $user->errors();
	}

	public function destroy($id)
	{
		$user = User::find($id);
		return $user->delete();
	}

}