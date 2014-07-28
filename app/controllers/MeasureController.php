<?php

use Launch\Scheduler\Scheduler;
use Launch\Scheduler\Timezone;
use Carbon\Carbon;

class MeasureController extends BaseController {

    public function test()
    {
        $date = Carbon::now()->subMonth(1);
        $now = Carbon::now();

        do {
            Scheduler::measureCreatedContent($date->format('Y-m-d'), 1);
            Scheduler::measureLaunchedContent($date->format('Y-m-d'), 1);
            Scheduler::measureTimingContent($date->format('Y-m-d'), 1);

            $date->addDay(1);
        } while ($now->gte($date));

        Scheduler::measureUserEfficiency($now->format('Y-m-d'), 1);
    }

    public function contentCreated($accountID)
    {
        $startDate = Input::get('start_date');
        if (!$startDate) {
            // default last 7 days
            $startDate = Carbon::now()->subWeek(1);
        }

        return MeasureCreatedContent::where('account_id', $accountID)->where('date', '>=', substr($startDate, 0, 10))->get();
    }

    public function contentLaunched($accountID)
    {
        $startDate = Input::get('start_date');
        if (!$startDate) {
            // default last 7 days
            $startDate = Carbon::now()->subWeek(1);
        }

        return MeasureLaunchedContent::where('account_id', $accountID)->where('date', '>=', substr($startDate, 0, 10))->get();
    }

    public function contentTiming($accountID)
    {
        $startDate = Input::get('start_date');
        if (!$startDate) {
            // default last 7 days
            $startDate = Carbon::now()->subWeek(1);
        }

        return MeasureTimingContent::where('account_id', $accountID)->where('date', '>=', substr($startDate, 0, 10))->get();
    }

    public function userEfficiency($accountID)
    {
       return UserEfficiency::where('account_id', $accountID)->get();
    }

    //////////////////////
    // Scheduled Tasks! //
    //////////////////////

    public function measureCreatedContent($date, $accountID)
    {
        $date = new Carbon($date);

        // @TODO get this from a config?
        // For now, using PDT since all 3 devs are on the west coast
        Timezone::set('-07:00');

        $count = DB::raw('count(*) as count');
        $query = Content::where('created_at', '>=', $date->copy()->startOfDay())
                        ->where('created_at', '<', $date->copy()->endOfDay())
                        ->where('account_id', $accountID)
                        ->where('status', '!=', 0);

        $model = MeasureCreatedContent::firstOrNew(['date' => $date->format('Y-m-d')]);
        $model->account_id = $accountID;

        $stats = [];

        $stats['by_user']         = with(clone $query)->select([$count, 'user_id'])->groupBy('user_id')->get()->toArray();
        $stats['by_buying_stage'] = with(clone $query)->select([$count, 'buying_stage'])->groupBy('buying_stage')->get()->toArray();
        $stats['by_content_type'] = with(clone $query)->select([$count, 'content_type_id'])->groupBy('content_type_id')->get()->toArray();

        $model->stats = $stats;

        $model->save();
    }

    public function measureLaunchedContent($date, $accountID)
    {
        $date = new Carbon($date);

        // @TODO get this from a config?
        // For now, using PDT since all 3 devs are on the west coast
        Timezone::set('-07:00');

        $count = DB::raw('count(*) as count');
        $query = Content::where('launch_date', '>=', $date->copy()->startOfDay())
                        ->where('launch_date', '<', $date->copy()->endOfDay())
                        ->where('account_id', $accountID)
                        ->where('status', '!=', 0);

        $model = MeasureLaunchedContent::firstOrNew(['date' => $date->format('Y-m-d')]);
        $model->account_id = $accountID;

        $stats = [];

        $stats['by_user']         = with(clone $query)->select([$count, 'user_id'])->groupBy('user_id')->get()->toArray();
        $stats['by_buying_stage'] = with(clone $query)->select([$count, 'buying_stage'])->groupBy('buying_stage')->get()->toArray();
        $stats['by_content_type'] = with(clone $query)->select([$count, 'content_type_id'])->groupBy('content_type_id')->get()->toArray();

        $model->stats = $stats;

        $model->save();
    }

    public function measureTimingContent($date, $accountID)
    {
        $date = new Carbon($date);

        // @TODO get this from a config?
        // For now, using PDT since all 3 devs are on the west coast
        Timezone::set('-07:00');

        $average = DB::raw('AVG(TIME_TO_SEC(TIMEDIFF(`launch_date`, `created_at`))) as average_seconds');
        $query = Content::where('launch_date', '>=', $date->copy()->subMonth(1)->startOfDay())
                        ->where('launch_date', '<', $date->copy()->endOfDay())
                        ->where('account_id', $accountID)
                        ->whereNotNull('launch_date');

        $model = MeasureTimingContent::firstOrNew(['date' => $date->format('Y-m-d')]);
        $model->account_id = $accountID;

        $stats = [];

        $stats['by_user']         = with(clone $query)->select([$average, 'user_id'])->groupBy('user_id')->get()->toArray();
        $stats['by_buying_stage'] = with(clone $query)->select([$average, 'buying_stage'])->groupBy('buying_stage')->get()->toArray();
        $stats['by_content_type'] = with(clone $query)->select([$average, 'content_type_id'])->groupBy('content_type_id')->get()->toArray();

        $model->stats = $stats;

        $model->save();
    }

    public function measureUserEfficiency($accountID)
    {
        $userIDs = User::select('users.id as id')
                       ->join('account_user', 'users.id', '=', 'account_user.user_id')
                       ->join('accounts', 'accounts.id', '=', 'account_user.account_id')
                       ->where('account_id', $accountID)->lists('id');

        $userIDs = array_unique($userIDs);
        $count = DB::raw('COUNT(*) as count');

        foreach ($userIDs as $userID) {
            $model = UserEfficiency::firstOrNew([
                'user_id' => $userID, 
                'account_id' => $accountID,
            ]);

            $base                           = ContentTask::select($count)->where('user_id', $userID);
            $all                            = with(clone $base)->first();
            $completed                      = with(clone $base)->where('is_complete', 1)->first();
            $model->completed_content_tasks = $all->count === 0 ? null : $completed->count / $all->count;

            $base                            = CampaignTask::select($count)->where('user_id', $userID);
            $all                             = with(clone $base)->first();
            $completed                       = with(clone $base)->where('is_complete', 1)->first();
            $model->completed_campaign_tasks = $all->count === 0 ? null : $completed->count / $all->count;

            $base                      = Content::select($count)->where('user_id', $userID);
            $all                       = with(clone $base)->whereRaw('((status >= 1 AND convert_date IS NOT NULL) OR (status = 0))')->first();
            $concepts                  = with(clone $base)->where('status', '>=', 1)->whereNotNull('convert_date')->first();
            $model->converted_concepts = $all->count === 0 ? null : $concepts->count / $all->count;

            $base                        = ContentTask::select($count)->where('user_id', $userID);
            $all                         = with(clone $base)->first();
            $completed                   = with(clone $base)->where('is_complete', 1)->where('date_completed', '<', 'due_date')->first();
            $model->early_campaign_tasks = $all->count === 0 ? null : $completed->count / $all->count;

            $base                        = CampaignTask::select($count)->where('user_id', $userID);
            $all                         = with(clone $base)->first();
            $completed                   = with(clone $base)->where('is_complete', 1)->where('date_completed', '<', 'due_date')->first();
            $model->early_campaign_tasks = $all->count === 0 ? null : $completed->count / $all->count;

            $model->save();
        }
    }

}
