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

        foreach($accounts as $accountUser) {
            $account = Account::find($accountUser->account_id);
            if($account->account_type != 'client') {
                // Prefer agency or pro accounts over clients.
                return Redirect::to("/account/{$account->id}");
            }
        }

        # If no agency account found, any other is fine.
        foreach($accounts as $account) {
            return Redirect::to("/account/{$account->account_id}");
        }
    }


}
