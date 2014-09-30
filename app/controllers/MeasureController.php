<?php

use Launch\Connections\API\ConnectionConnector;
use Launch\Scheduler\Scheduler;
use Launch\Scheduler\Timezone;
use Carbon\Carbon;

class MeasureController extends BaseController {

    public function runConnection() {
        $connection = AccountConnection::find(27);
        //var_dump($connection);
        $twitter = ConnectionConnector::loadAPI('twitter', $connection);
        echo $twitter->getContent(27);
        die;
    }

    public function updateStats($accountID)
    {
        $now = Carbon::now();

        $this->measureCreatedContent($now->format('Y-m-d'), $accountID);
        $this->measureLaunchedContent($now->format('Y-m-d'), $accountID);
        $this->measureTimingContent($now->format('Y-m-d'), $accountID);
        $this->measureContentScore($now->format('Y-m-d'), $accountID);


        $this->measureUserEfficiency($now->format('Y-m-d'), $accountID);
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

    public function contentScore($accountID)
    {
        $startDate = Input::get('start_date');
        if (!$startDate) {
            // default last 7 days
            $startDate = Carbon::now()->subWeek(1);
        }

        return MeasureContentScore::where('account_id', $accountID)->where('date', '>=', substr($startDate, 0, 10))->get();
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

        $model = MeasureLaunchedContent::firstOrNew(['date' => $date->format('Y-m-d'), 'account_id' => $accountID]);

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

        $model = MeasureTimingContent::firstOrNew(['date' => $date->format('Y-m-d'), 'account_id' => $accountID]);

        $stats = [];

        $stats['by_user']         = with(clone $query)->select([$average, 'user_id'])->groupBy('user_id')->get()->toArray();
        $stats['by_buying_stage'] = with(clone $query)->select([$average, 'buying_stage'])->groupBy('buying_stage')->get()->toArray();
        $stats['by_content_type'] = with(clone $query)->select([$average, 'content_type_id'])->groupBy('content_type_id')->get()->toArray();

        $model->stats = $stats;

        $model->save();
    }

    public function measureContentScore($date, $accountID) {
        $date = new Carbon($date);

        // @TODO get this from a config?
        // For now, using PDT since all 3 devs are on the west coast
        Timezone::set('-07:00');

        $score = DB::raw('sum(content_scores.score) as score');
        $query = DB::table('content')
            ->join('content_scores', 'content.id', '=', 'content_scores.content_id')
            ->where('content_scores.date', $date)
            ->where('account_id', $accountID)
            ->where('status', '!=', 0);

        //not sure if score content score should show all content or just content launched that day
        //scoring algorithm gives scores for all content on all days so I'm going to stick with that
//            ->where('launch_date', '>=', $date->copy()->startOfDay())
//            ->where('launch_date', '<', $date->copy()->endOfDay())


        $model = MeasureContentScore::firstOrNew(['date' => $date->format('Y-m-d'), 'account_id' => $accountID]);

        $stats = [];

        $stats['by_user']         = with(clone $query)->select([$score, 'user_id'])->groupBy('user_id')->get();
        $stats['by_buying_stage'] = with(clone $query)->select([$score, 'buying_stage'])->groupBy('buying_stage')->get();
        $stats['by_content_type'] = with(clone $query)->select([$score, 'content_type_id'])->groupBy('content_type_id')->get();

        var_dump($stats);

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

    /**
     * Get the automation stats for content that
     * has been promoted to hubspot and/or acton
     * Stats are cached for an hour
     */
    public function getAutomationStats($accountID)
    {
        if ( ! $this->inAccount($accountID)) {
            return $this->responseAccessDenied();
        }
        $stats = [];

        // Get all launches to hubspot
        $launches = LaunchResponse::where('success', 1)
            ->whereHas('account_connection', function ($query) {
                $query->whereHas('connection', function ($query) {
                    $query->where('provider', '=', 'hubspot');
                });
            })
            ->with(['account_connection.connection' => function ($query) {
                $query->where('provider', '=', 'hubspot');
            }])
            ->with('content.user')
            ->with('content.content_type')
            ->get();

        foreach ($launches as $launch) {
            $automation = null;
            // Check cache 
            $cacheKey = 'launch-stats:'. $accountID .':'. $launch->id;
            if ( ! Input::get('refresh') && Cache::has($cacheKey)) {
                $automation = Cache::get($cacheKey);
            } else {
                $api = ConnectionConnector::loadAPI('hubspot', $launch->account_connection);
                if (method_exists($api, 'getStats')) {
                    $automation = $api->getStats($launch);
                    $expires = Carbon::now()->addMinutes(60);
                    Cache::put($cacheKey, $automation, $expires);
                }
            }
            if ( ! empty($automation)) {
                $record = $launch->content->toArray();
                $record['automation'] = $automation;
                $stats[] = $record;
            }
        }
        return $stats;
    }

}
