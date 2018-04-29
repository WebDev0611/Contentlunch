<?php

namespace App\Tasks;

use DB;
//use Cartalyst\Stripe\Stripe;
use Stripe\Stripe;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;

class QebotBilling {

    public static $qebotStripeId    = 'cus_ClrxciVMFhRbRf';
    public static $qebotBankId      = 'ba_1CMKTpHZGHkxLfhUXnZEpBaR';

    public static function init () 
    {
        // Get Active Users with an enabled active account and be a qebot user
       $activeUsers = self::_getActiveUsers();
       $amount = $activeUsers * 5;
       $charge = self::_billStripe(self::$qebotStripeId, self::$qebotBankId, $amount);

       self::_sendMonthlyEmailReport($amount, $charge)
    }

    public static function verify ($one, $two)
    {
        $customer = \Stripe\Customer::retrieve($user);
        $bank_account = $customer->sources->retrieve($bank);
        $verify = $bank_account->verify(array('amounts' => array($one, $two)));

        dd($verify);
    }
    private static function _getActiveUsers ()
    {
        $db  = DB::table('users')->where('users.qebot_user', 1)->join('accounts', function($query) {
                    $query->on('users.account_id', '=', 'accounts.id');
                })->where('accounts.enabled',1)->count();
        return $db;
    }
    private static function _billStripe ($user, $bank, $amount) 
    {
        Stripe::setApiKey(Config::get('services.stripe.secret'));

        //$customer = \Stripe\Customer::retrieve($user);
       // $bank_account = $customer->sources->retrieve($bank);
       // $bank_account->verify(array('amounts' => array({FIRST_AMOUNT}, {SECOND_AMOUNT})));

       // dd($customer);
        $charge = \Stripe\Charge::create(array(
          "amount" => ($amount * 100),
          "customer" => $user,
          "currency" => "usd",
          "description" => "Monthly Active User Bill"
        ));

        return $charge;     
    }

    private static function _sendMonthlyEmailReport($amount, $charge) {

        $now = Carbon::now('America/Los_Angeles');

        $data = compact('amount', 'charge', 'now');
         Mail::send('emails.qebot_billing', $data, function($message) use ($now) {
            $message->from("no-reply@contentlaunch.com", "Content Launch")
                //->to('jon@contentlaunch.com')
                ->to('topsub@gmail.com')
                ->subject('Content Launch Qebot Report ' . $now->format('m/d/Y'));
        });
    }
}