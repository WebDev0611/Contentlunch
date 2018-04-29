<?php

namespace App\Console;

use App\Http\Controllers\WriterAccessCommentController;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        // Commands\Inspire::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        if(env('APP_ENV') == 'production') {
            $schedule->call('App\Tasks\DailyReport@sendEmailReport')
                ->dailyAt('08:00')
                ->timezone('America/Los_Angeles');

            $schedule->call('App\Tasks\DailyReport@sendPrescriptionsEmailReport')
                ->dailyAt('08:05')
                ->timezone('America/Los_Angeles');
        }

        // Check WriterAccess orders every 15 min
        $schedule->call('App\Http\Controllers\WriterAccessOrdersController@fetch')->cron('*/15 * * * * *');

        // Check for new WriterAccess comments
        $schedule->call('App\Http\Controllers\WriterAccessCommentController@fetch')->everyThirtyMinutes();

        // Check for Pending Content Orders
        $schedule->call('App\Tasks\OrderReports@sendPendingOrdersNotification')->daily();


        // Monthly Billing for Qebot Users
        //$schedule->call('App\Tasks\QebotBilling@init')->monthly();
    }
}
