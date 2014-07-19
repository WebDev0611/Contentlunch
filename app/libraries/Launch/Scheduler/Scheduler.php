<?php namespace Launch\Scheduler;

use \Carbon\Carbon;
use \Queue;
use \Log;
use \Exception;
use \DB;

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
        // @TODO configurable
        Timezone::set('-07:00');

        $date = new Carbon($date);
        Queue::later($date->endOfDay(), function () use ($date, $accountID) {
            App::make('Measure')->measureCreatedContent($date, $accountID);

            // schedule for tomorrow
            self::measureCreatedContent($date->tomorrow()->endOfDay(), $accountID);
        }, [$date->format('Y-m-d'), $accountID], 'measure-created-content');
    }

    public static function measureLaunchedContent($date, $accountID)
    {
        // @TODO configurable
        Timezone::set('-07:00');

        $date = new Carbon($date);
        Queue::later($date->endOfDay(), function () use ($date, $accountID) {
            App::make('Measure')->measureLaunchedContent($date, $accountID);

            // schedule for tomorrow
            self::measureLaunchedContent($date->tomorrow()->endOfDay(), $accountID);
        }, [$date->format('Y-m-d'), $accountID], 'measure-launched-content');
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