<?php
/**
 * Confide user controller
 */
class AuthController extends BaseController {

  /**
   * Get the active logged in user
   * @return [type] [description]
   */
  public function show_current()
  {
    if ($user = Confide::user()) {
      $ctrl = new UserController;
      return $ctrl->callAction('show', [$user->id]);
    }
    return Response::json(['username' => 'guest']);
  }

  /**
   * Attempt to do login
   *
   */
  public function do_login()
  {
    $input = [
      'email'    => Input::get( 'email' ), // May be the username too
      'username' => Input::get( 'email' ), // so we have to pass both
      'password' => Input::get( 'password' ),
      'remember' => Input::get( 'remember' ),
    ];

    // Ugly hack to get remember_token past Ardent validation
    // Should be done for logout too
    User::$rules = [];
    
    Session::forget('guest');

    // If you wish to only allow login from confirmed users, call logAttempt
    // with the second parameter as true.
    // logAttempt will check if the 'email' perhaps is the username.
    // Get the value from the config file instead of changing the controller
    if ( Confide::logAttempt( $input, Config::get('confide::signup_confirm') ) )
    {
      // Redirect the user to the URL they were trying to access before
      // caught by the authentication filter IE Redirect::guest('user/login').
      // Otherwise fallback to '/'
      // Fix pull #145
      //return Redirect::intended('/'); // change it to '/admin', '/dashboard' or something
      //
      //$id = User::where('email', $input['email'])->pluck('id');
      $user = Confide::user();
      // Don't let inactive users login
      if ( ! $user->status) {
        Confide::logout();
        return $this->responseError("Account is inactive.", 401);
      }
      $ctrl = new UserController;
      return $ctrl->callAction('show', [$user->id]);
    }
    else
    {
      $user = new User;

      // Check if there was too many login attempts
      if( Confide::isThrottled( $input ) )
      {
        $err_msg = Lang::get('confide::confide.alerts.too_many_attempts');
      }
      elseif( $user->checkUserExists( $input ) and ! $user->isConfirmed( $input ) )
      {
        $err_msg = Lang::get('confide::confide.alerts.not_confirmed');
      }
      else
      {
        $err_msg = Lang::get('confide::confide.alerts.wrong_credentials');
      }
      return $this->responseError($err_msg, 401);
    }
  }

  /**
   * Attempt to confirm account with code
   *
   * @param  string  $code
   */
  public function do_confirm()
  {
    $code = Input::get('code');

    $user = User::where('confirmation_code', $code)->first();
    if ($user) {
      
      // Check if this code was generated within 24 hours
      if ( (strtotime($user->updated_at) - strtotime('-24 hours')) > 0) {
        if (Confide::confirm($code))
        {
          // Set user to active
          $user->status = 1;
          $user->updateUniques();
          Auth::login($user);
          return $this->show_current();
        }
      }
    }
    $error_msg = Lang::get('confide::confide.alerts.wrong_confirmation');
    return $this->responseError($error_msg);
  }

  /**
   * Check if this token is still valid
   * Should be valid for 24 hours
   */
  public function check_reset($code)
  {
    $reset = DB::table('password_reminders')->where('token', $code)->first();
    if ($reset) {
      // Piggyback off confirm functionality since they 
      // pretty much do the same thing
      $user = User::where('email', $reset->email)->first();
      // Confirm will check updated_at for 24 hours, so update that
      $date = new DateTime;
      $user->updated_at = $date;
      $user->updateUniques();
      if (strtotime($reset->created_at) - strtotime('-24 hours') > 0) {
        // Valid token, redirect to reset password
        return Redirect::to('user/confirm/'. $user->confirmation_code);
      }
    }
    // Redirect to login with invalid link
    return Redirect::to('login?link=expired');
  }

  /**
   * Attempt to send change password link to the given email
   *
   */
  public function do_forgot_password()
  {
    if( Confide::forgotPassword( Input::get( 'email' ) ) )
    {
      $notice_msg = Lang::get('confide::confide.alerts.password_forgot');
      return ['message' => $notice_msg];
    }
    else
    {
      $error_msg = Lang::get('confide::confide.alerts.wrong_password_forgot');
      return $this->responseError($error_msg);
    }
  }

  /**
   * Attempt change password of the user
   *
   */
  public function do_reset_password()
  {
    $input = [
      'token' => Input::get( 'token' ),
      'password' => Input::get( 'password' ),
      'password_confirmation' => Input::get( 'password_confirmation' ),
    ];

    // By passing an array with the token, password and confirmation
    if( Confide::resetPassword( $input ) )
    {
      $notice_msg = Lang::get('confide::confide.alerts.password_reset');
      return ['message' => $notice_msg];
    }
    else
    {
      $error_msg = Lang::get('confide::confide.alerts.wrong_password_reset');
      return $this->responseError($error_msg);
    }
  }

  /**
   * Log the user out of the application.
   *
   */
  public function logout()
  {
    User::$rules = [];
    Session::forget('guest');
    Confide::logout();
    Session::put('impersonate_from', false);
    return ['success' => 'OK'];
  }

  /**
   * Impersonate as a user
   */
  public function impersonate()
  {
    if (Input::has('account_id')) {
      $id = Input::get('account_id');
      // Store a reference to current logged in user so we
      // can switch back
      $currentUser = Confide::user();
      $account = Account::find($id);
      // Find the site admin user for this account
      $siteAdminUser = $account->getSiteAdminUser();
      if ($currentUser && $siteAdminUser) {
        Auth::login($siteAdminUser);
        Session::put('impersonate_from', $currentUser->id);
        $ctrl = new UserController;
        return $ctrl->callAction('show', [$siteAdminUser->id]);
      }
   } elseif (Input::get('reset')) {
      $from = Session::get('impersonate_from');
      if ($from) {
        $user = User::find($from);
        Auth::login($user);
        $ctrl = new UserController;
        return $ctrl->callAction('show', [$user->id]);
      }
    }
    return $this->responseError("Access denied.", 401);
  }

}
