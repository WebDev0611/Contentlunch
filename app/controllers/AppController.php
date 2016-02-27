<?php

/** This controller deals with getting a user into the angular app.
 *
 */

use \Carbon\Carbon;

/**
 * Confide user controller
 */
class AppController extends BaseController
{

    function account($accountId) {
        return View::make('master', ['accountId' => $accountId]);
    }

    function home() {
        $user = Auth::user();
        $accounts = AccountUser::where('user_id', $user->id)->get();
        foreach($accounts as $account) {
            if($account != 'client') {
                // Prefer agency or pro accounts over clients.
                return Redirect::to("/account/{$account->id}");
            }
        }
        foreach($accounts as $account) {
            return Redirect::to("/account/{$account->id}");
        }
    }


}
