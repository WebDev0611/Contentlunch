<?php

class BaseController extends Controller {

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}

	/**
   * Return a error json response
   * @param  array  $data Data to json encode
   * @return json Response
   */
  protected function responseError($data, $status = null)
  {
    return self::staticResponseError($data, $status);
  }

  static function staticResponseError($data, $status = null)
  {
    $data = (array) $data;
    $status = $status ? $status : 400;
    return Response::json(array('errors' => $data), $status);
  }

  /**
   * Return an access denied response
   * @return json Response
   */
  protected function responseAccessDenied()
  {
    return Response::json(array('errors' => 'Access Denied'), 401);
  }

  /**
   * Check if user has ability
   * @return boolean Can access
   */
  protected function hasAbility($roles, $permissions = array(), $options = array())
  {
    if (app()->environment() == 'testing') {
      return true;
    }
    if ($this->hasRole('global_admin')) {
      return true;
    }
    $user = Confide::user();
    if ($user) {
      return $user->ability($roles, $permissions, $options);
    }
    return false;
  }

  /**
   * Check if user has a role
   * @param string $roleName
   * @return boolean Has role
   */
  protected function hasRole($roleName)
  {
    if (app()->environment() == 'testing') {
      return true;
    }
    if (Entrust::hasRole('global_admin')) {
      return true;
    }
    return Entrust::hasRole($roleName);
  }

  protected function hasPermission($permission)
  {
    $user = Confide::user();
    $user = User::with('roles')->find($user->id);
    if ($user) {
      return $user->can($permission);
    }
    return false;
  }

  protected function inAccount($accountId)
  {
    if ($this->hasRole('global_admin')) {
      return true;
    }
    $user = Confide::user();
    if ($user) {
      $id = DB::table('account_user')
        ->where('account_id', $accountId)
        ->where('user_id', $user->id)
        ->pluck('id');
      if ($id) {
        return true;
      }
    }
    return false;
  }

}
