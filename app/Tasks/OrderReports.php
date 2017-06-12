<?php

namespace App\Tasks;

use App\User;
use App\WriterAccessOrder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class OrderReports {

    public static function sendPendingOrdersNotification ()
    {
        WriterAccessOrder::where('status', 'Pending Approval')->each(function ($order) {
            $user = User::where('writer_access_Project_id', (string)$order->project->id)->first();
            if ($user) {
                Mail::send('emails.writeraccess_pending_order', [], function ($message) use ($user) {
                    $message->from("no-reply@contentlaunch.com", "Content Launch")
                        ->to($user->email)
                        ->subject('You have Content Orders that are pending approval');
                });
            }
        });
    }
}