<?php

namespace App\Providers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Queue::failing(function (JobFailed $event) {
            $mailData = [
                'connectionName' => $event->connectionName,
                'job' => $event->job,
                'data' => $event->data
            ];
            Mail::send('emails.queued_job_failed', ['data' => $mailData], function($message) {
                $message->from("no-reply@contentlaunch.com", "Content Launch")
                    ->to('jon@contentlaunch.com')
                    ->subject('Queued job failed!');
            });
        });

        $this->app['request']->server->set('HTTPS', $this->app->environment() != 'local');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
