<?php namespace Launch\Scheduler;

use \Carbon\Carbon;
use \Queue;
use \Log;
use \Exception;
use \DB;
use \App;

use \User;
use \Content;
use \MeasureCreatedContent;

class Scheduler {
    
    public static function videoConferenceReminder($conferenceModel) 
    {
        if (!$conferenceModel->scheduled_date) {
            return true;
        }

        $conference = $conferenceModel->toArray();

        if (is_string($conference['scheduled_date'])) {
            $date = Carbon::parse($conference['scheduled_date']);
        } else {
            $date = Carbon::instance($conference['scheduled_date']);
        }

        $whens = [
            $date->copy()->subHours(1), 
            $date->copy()->subHours(24),
        ];

        // @TODO: Global Admin is always ID 1?
        $globalAdmin = User::find(1)->toArray();
        $globalAdmin['name'] = "{$globalAdmin['first_name']} {$globalAdmin['last_name']}";

        $tokens = [];
        foreach ($whens as $when) {
            Log::info('Scheduling emailReminder job: ' . $when);
            $tokens[] = Queue::later($when, 'ConferencesController@emailReminder', [
                'globalAdmin' => $globalAdmin,
                'conference' => $conference,
            ], 'conference-reminders');
        }

        if (is_array($conference['tokens'])) {
            self::deleteByTokens($conference['tokens']);
        }

        $conferenceModel->tokens = $tokens;
        return $conferenceModel->save();
    }

    public static function measureCreatedContent($date, $accountID)
    {
        Log::info('Queueing measureCreatedContent job');
        // @TODO configurable
        Timezone::set('-07:00');

        $date = new Carbon($date);
        Queue::later($date->endOfDay(), function ($job) use ($date, $accountID) {
        // Queue::push(function ($job) use ($date, $accountID) {
            Log::info('Running measureCreatedContent job');
            App::make('Measure')->measureCreatedContent($date, $accountID);

            // schedule for tomorrow
            \Launch\Scheduler\Scheduler::measureCreatedContent($date->tomorrow()->endOfDay(), $accountID);

            $job->delete();
        }, [$date->format('Y-m-d'), $accountID]); //, 'measure-created-content');
    }

    public static function measureLaunchedContent($date, $accountID)
    {
        Log::info('Queueing measureLaunchedContent job');
        // @TODO configurable
        Timezone::set('-07:00');

        $date = new Carbon($date);
        Queue::later($date->endOfDay(), function ($job) use ($date, $accountID) {
        // Queue::push(function ($job) use ($date, $accountID) {
            Log::info('Running measureLaunchedContent job');
            App::make('Measure')->measureLaunchedContent($date, $accountID);

            // schedule for tomorrow
            \Launch\Scheduler\Scheduler::measureLaunchedContent($date->tomorrow()->endOfDay(), $accountID);

            $job->delete();
        }, [$date->format('Y-m-d'), $accountID]); //, 'measure-launched-content');
    }

    public static function measureTimingContent($date, $accountID)
    {
        Log::info('Queueing measureTimingContent job');
        // @TODO configurable
        Timezone::set('-07:00');

        $date = new Carbon($date);
        // Queue::later($date->endOfDay(), function ($job) use ($date, $accountID) {
        Queue::push(function ($job) use ($date, $accountID) {
            Log::info('Running measureTimingContent job');
            App::make('Measure')->measureTimingContent($date, $accountID);

            // schedule for tomorrow
            \Launch\Scheduler\Scheduler::measureTimingContent($date->addMonth(1)->endOfDay(), $accountID);

            $job->delete();
        }, [$date->format('Y-m-d'), $accountID]); //, 'measure-timing-content');
    }

    /**
     * Delete scheduled tasks before they occur.
     * @param  string|int $token Token matching scheduled job you want to delete
     * @return void
     */
    public static function deleteByToken($token)
    {
        // there are situations where a job ID may not exist
        // and if that's the case, this will throw an error
        try {
            $pheanstalk = Queue::getPheanstalk();
            $job = $pheanstalk->peek($token);
            $pheanstalk->delete($job);
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }

    public static function deleteByTokens($tokens)
    {
        foreach ($tokens as $token) {
            self::deleteByToken($token);
        }
    }
    
}