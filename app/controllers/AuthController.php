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
            return $ctrl->callAction('show', array($user->id));
        }
        return Response::json(array('username' => 'guest'));
    }

    /**
     * Attempt to do login
     *
     */
    public function do_login()
    {
        $input = array(
            'email'    => Input::get( 'email' ), // May be the username too
            'username' => Input::get( 'email' ), // so we have to pass both
            'password' => Input::get( 'password' ),
            'remember' => Input::get( 'remember' ),
        );

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
            return $ctrl->callAction('show', array($user->id));
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
        if (Confide::confirm($code))
        {
            $user = User::where('confirmation_code', $code)->first();
            // Set user to active
            $user->status = 1;
            $user->updateUniques();
            Auth::login($user);
            return $this->show_current();
        }
        else
        {
            $error_msg = Lang::get('confide::confide.alerts.wrong_confirmation');
            return $this->responseError($error_msg);
        }
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
            return array('message' => $notice_msg);
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
        $input = array(
            'token' => Input::get( 'token' ),
            'password' => Input::get( 'password' ),
            'password_confirmation' => Input::get( 'password_confirmation' ),
        );

        // By passing an array with the token, password and confirmation
        if( Confide::resetPassword( $input ) )
        {
            $notice_msg = Lang::get('confide::confide.alerts.password_reset');
            return array('message' => $notice_msg);
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
        Confide::logout();
        return array('success' => 'OK');
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
              return $ctrl->callAction('show', array($siteAdminUser->id));
            }
        } elseif (Input::get('reset')) {
            $from = Session::get('impersonate_from');
            if ($from) {
                $user = User::find($from);
                Auth::login($user);
                $ctrl = new UserController;
                return $ctrl->callAction('show', array($user->id));
            }
        }
        return $this->responseError("Access denied.", 401);
    }

}
