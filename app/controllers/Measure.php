<?php

use Launch\Scheduler\Scheduler;

class Measure extends BaseController {

    public function test()
    {
        Scheduler::measureCreatedContent('2014-07-18', 1);
        Scheduler::measureLaunchedContent('2014-07-18', 1);
    }

    public function createdContent()
    {
        if ($startDate == Input::get('start_date')) {
            return MeasureCreatedContent::where('date', '>=', substr($startDate, 0, 10))->get();
        }

        return MeasureCreatedContent::all();
    }

    public function launchedContent()
    {
        if ($startDate == Input::get('start_date')) {
            return MeasureLaunchedContent::where('date', '>=', substr($startDate, 0, 10))->get();
        }

        return MeasureLaunchedContent::all();
    }



    //////////////////////
    // Scheduled Tasks! //
    //////////////////////

    public  function measureCreatedContent($date, $accountID)
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

        $model = MeasureCreatedContent::firstOrNew(['date' => $date]);
        $model->accountID = $accountID;

        $stats = [];

        $stats['by_user']         = with(clone $query)->select([$count, 'user_id'])->groupBy('user_id')->get()->toArray();
        $stats['by_buying_stage'] = with(clone $query)->select([$count, 'buying_stage'])->groupBy('buying_stage')->get()->toArray();
        $stats['by_content_type'] = with(clone $query)->select([$count, 'content_type_id'])->groupBy('content_type_id')->get()->toArray();

        $model->stats = $stats;

        $model->save();
    }

    public  function measureLaunchedContent($date, $accountID)
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

        $model = MeasureCreatedContent::firstOrNew(['date' => $date]);
        $model->accountID = $accountID;

        $stats = [];

        $stats['by_user']         = with(clone $query)->select([$count, 'user_id'])->groupBy('user_id')->get()->toArray();
        $stats['by_buying_stage'] = with(clone $query)->select([$count, 'buying_stage'])->groupBy('buying_stage')->get()->toArray();
        $stats['by_content_type'] = with(clone $query)->select([$count, 'content_type_id'])->groupBy('content_type_id')->get()->toArray();

        $model->stats = $stats;

        $model->save();
    }

}
