<?php namespace Launch\Scheduler;

use \Carbon\Carbon;
use \Queue;
use \Log;

class Scheduler {
    public static function videoConferenceReminder($conference) 
    {
        Log::info('Scheduling emailReminder job.');
        $when = Carbon::now()->addSeconds(5);
        Queue::later(5, 'ConferencesController@emailReminder', [
        // Queue::push('ConferencesController@emailReminder', [
            'globalAdmin' => [
                'name' => 'Cameron Spear',
                'email' => 'cspear@surgeforward.com',
            ],
            'conference' => [
                'date' => '2014-07-04 05:55:55'
            ]
        ]);
    }
}