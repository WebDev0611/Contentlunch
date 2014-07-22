<?php

use Launch\Scheduler\Scheduler;
use Launch\Scheduler\Timezone;
use Carbon\Carbon;

class Measure extends BaseController {

    public function test()
    {
        $date = Carbon::now()->subMonth(1);
        $now = Carbon::now();

        // do {
        //     Scheduler::measureCreatedContent($date->format('Y-m-d'), 1);
        //     Scheduler::measureLaunchedContent($date->format('Y-m-d'), 1);
        //     $date->addDay(1);
        // } while ($now->gte($date));

        // Queue::push('Measure');

        Scheduler::measureCreatedContent($now->format('Y-m-d'), 1);
        Scheduler::measureLaunchedContent($now->format('Y-m-d'), 1);
    }

    public function fire()
    {
        Log::info('Testing the FIRE');
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

        $model = MeasureCreatedContent::firstOrNew(['date' => $date->format('Y-m-d')]);
        $model->account_id = $accountID;

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

        $model = MeasureLaunchedContent::firstOrNew(['date' => $date->format('Y-m-d')]);
        $model->account_id = $accountID;

        $stats = [];

        $stats['by_user']         = with(clone $query)->select([$count, 'user_id'])->groupBy('user_id')->get()->toArray();
        $stats['by_buying_stage'] = with(clone $query)->select([$count, 'buying_stage'])->groupBy('buying_stage')->get()->toArray();
        $stats['by_content_type'] = with(clone $query)->select([$count, 'content_type_id'])->groupBy('content_type_id')->get()->toArray();

        $model->stats = $stats;

        $model->save();
    }

}
