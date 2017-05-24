<?php

namespace App\Tasks;

use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

/**
* Daily report class
*/
class DailyReport
{
    /**
     * Send email with a report on registered users
     * on the last 24h.
     */
    public static function sendEmailReport()
    {
        $users = User::createdSinceYesterday()->get();
        $now = Carbon::now('America/Los_Angeles');
        $yesterday = Carbon::now('America/Los_Angeles')->subDay();

        $data = compact('users', 'now', 'yesterday');

        Mail::send('emails.user_report', $data, function($message) use ($now) {
            $message->from("no-reply@contentlaunch.com", "Content Launch")
                ->to('jon@contentlaunch.com')
                ->subject('Content Launch Daily User Report ' . $now->format('m/d/Y'));
        });
    }

    /**
     * Sends email report about used prescriptions in the last 24h
     */
    public function sendPrescriptionsEmailReport ()
    {
        $prescriptions = DB::table('content_prescription_user')->whereBetween('created_at', [
            Carbon::now()->subDay(),
            Carbon::now(),
        ])->get();

        $now = Carbon::now('America/Los_Angeles');
        $yesterday = Carbon::now('America/Los_Angeles')->subDay();

        $data = compact('prescriptions', 'now', 'yesterday');

        Mail::send('emails.prescription_report', $data, function($message) use ($now) {
            $message->from("no-reply@contentlaunch.com", "Content Launch")
                ->to('jon@contentlaunch.com')
                ->subject('Content Launch Daily Prescriptions Report ' . $now->format('m/d/Y'));
        });
    }
}
