<?php namespace Launch\Scheduler;

use \Carbon\Carbon;
use \Queue;
use \Log;

class Scheduler {
    
    public static function videoConferenceReminder($conference) 
    {
        Log::info('Scheduling emailReminder job.');
        $when = Carbon::now()->addSeconds(5);
        $token = Queue::later($when, 'ConferencesController@emailReminder', [
            'globalAdmin' => [
                'name' => 'Cameron Spear',
                'email' => 'cspear@surgeforward.com',
            ],
            'conference' => [
                'date' => '2014-07-04 05:55:55'
            ]
        ]);

        var_dump($token);
    }

    public static function deleteByToken($token)
    {
        $pheanstalk = Queue::getPheanstalk();
        $job = $pheanstalk->peek($token);
        $pheanstalk->delete($job);
    }

    public static function deleteByTokens($tokens)
    {
        foreach ($tokens as $token) {
            self::deleteByToken($token);
        }
    }
    
}