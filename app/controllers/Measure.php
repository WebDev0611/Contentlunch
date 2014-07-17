<?php

use Launch\Scheduler\Scheduler;

class Measure extends BaseController {

    public function test()
    {
        Scheduler::measureCreatedContent('2014-07-09');
        Scheduler::measureCreatedContent('2014-07-10');
        Scheduler::measureCreatedContent('2014-07-11');
    }

    public function contentCreated()
    {
        return MeasureCreatedContent::all();
    }

}
