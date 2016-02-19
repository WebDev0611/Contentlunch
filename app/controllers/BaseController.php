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
    return Response::json(['errors' => ['Access Denied']], 401);
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

  protected function hasPermission($accountId, $permission)
  {
    $user = Confide::user();
    if ($user) {
      $userObj = User::find($user->id);
      if ($userObj) {
        return $userObj->can($permission);
      }
    }
    return false;
  }

  protected function inAccount($accountId)
  {
    // Global admins can do anything.
    if ($this->hasRole('global_admin')) {
      return true;
    }

    // Make sure a user is logged in
    $user = Confide::user();
    if ($user) {

      // First, lets check the actual account specified
      $id = DB::table('account_user')->where('account_id', $accountId)->where('user_id', $user->id)->pluck('id');
      if ($id) {
        return true;
      }

      // If the account is a client account, we also have to check the parent agency account.
      $account = Account::find($accountId);
      if($account->account_type == 'client') {
        return $this->inAccount($account->parent_id);
      }


    }
    return false;
  }

  /**
   * Validation helper, determine if user
   * should have access to this account and content
   * @return bool $valid
   */
  protected function validateAccountContent($accountID, $contentID)
  {
    // Make sure user belongs to account
    if ( ! $this->inAccount($accountID)) {
      return false;
    }
    // Make sure content belongs to account
    $content = Content::find($contentID);
    if ( ! $content) {
      return false;
    }
    if ($content->account_id != $accountID) {
      return false;
    }
    return true;
  }

}
