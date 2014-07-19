<?php

use Launch\Scheduler\Scheduler;

class Measure extends BaseController {

    public function test()
    {
        Scheduler::measureCreatedContent('2014-07-09');
        Scheduler::measureCreatedContent('2014-07-10');
        Scheduler::measureCreatedContent('2014-07-11');
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
}
