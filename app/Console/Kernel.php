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
        $schedule->call('App\Tasks\DailyReport@sendEmailReport')
            ->dailyAt('10:00')
            ->timezone('America/Los_Angeles');

        // Check for new WriterAccess comments
        $schedule->call('App\Http\Controllers\WriterAccessCommentController@fetch')->everyTenMinutes();
    }
}
