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
        $date = new Carbon($date);

        // @TODO get this from a config?
        // For now, using PDT since all 3 devs are on the west coast
        Timezone::set('-07:00');

        $count = DB::raw('count(*) as count');
        $query = Content::where('created_at', '>=', $date->copy()->startOfDay())
                        ->where('created_at', '<', $date->copy()->endOfDay())
                        ->where('status', '!=', 0);

        $model = MeasureCreatedContent::firstOrNew(['date' => $date]);
        $model->accountID = $accountID;

        $stats = [];

        $stats['by_user']         = with(clone $query)->select([$count, 'user_id'])->groupBy('user_id')->get()->toArray();
        $stats['by_buying_stage'] = with(clone $query)->select([$count, 'buying_stage'])->groupBy('buying_stage')->get()->toArray();
        $stats['by_content_type'] = with(clone $query)->select([$count, 'content_type_id'])->groupBy('content_type_id')->get()->toArray();

        $model->stats = $stats;

        $model->save();
    }

    public static function measureLaunchedContent($date, $accountID)
    {
        $date = new Carbon($date);

        // @TODO get this from a config?
        // For now, using PDT since all 3 devs are on the west coast
        Timezone::set('-07:00');

        $count = DB::raw('count(*) as count');
        $query = Content::where('launch_date', '>=', $date->copy()->startOfDay())
                        ->where('launch_date', '<', $date->copy()->endOfDay())
                        ->where('status', '!=', 0);

        $model = MeasureCreatedContent::firstOrNew(['date' => $date]);
        $model->accountID = $accountID;

        $stats = [];

        $stats['by_user']         = with(clone $query)->select([$count, 'user_id'])->groupBy('user_id')->get()->toArray();
        $stats['by_buying_stage'] = with(clone $query)->select([$count, 'buying_stage'])->groupBy('buying_stage')->get()->toArray();
        $stats['by_content_type'] = with(clone $query)->select([$count, 'content_type_id'])->groupBy('content_type_id')->get()->toArray();

        $model->stats = $stats;

        $model->save();
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