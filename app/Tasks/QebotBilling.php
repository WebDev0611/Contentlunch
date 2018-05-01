<?php

namespace App\Tasks;

use DB;
use Stripe\Stripe;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;

class QebotBilling {

    public static $qebotStripeId    = 'cus_CmcZCnxDVOBkLJ';
    public static $qebotBankId      = 'ba_1CN3MzHZGHkxLfhUcc3Q9gtT';

    public static function init () 
    {
        // Get Active Users with an enabled active account and be a qebot user
       $activeUsers = self::_getActiveUsers();
       $amount = $activeUsers * 40;
       if($amount > 0) {
            $charge = self::_billStripe(self::$qebotStripeId, self::$qebotBankId, $amount);
       }
       else {
            Log::warn('No active users from qebot to bill.');
       }
       if($charge) {
            self::_sendMonthlyEmailReport($amount, $charge);
       }
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
        try {

            Stripe::setApiKey(Config::get('services.stripe.secret'));

            $charge = \Stripe\Charge::create(array(
              "amount" => ($amount * 100),
              "customer" => $user,
              "currency" => "usd",
              "description" => "Monthly Active User Bill"
            ));

            return $charge;   
        }
        catch (\Exception $e) {
            Log::critical('Error with stripe billing qebot');

        }  
    }

    private static function _sendMonthlyEmailReport($amount, $charge) {

        $now = Carbon::now('America/Los_Angeles');

        $data = compact('amount', 'charge', 'now');
         Mail::send('emails.qebot_billing', $data, function($message) use ($now) {
            $message->from("no-reply@contentlaunch.com", "Content Launch")
                ->to('jon@contentlaunch.com')
                //->to('topsub@gmail.com')
                ->subject('Content Launch Qebot Report ' . $now->format('m/d/Y'));
        });
    }
}