<?php

class StatusController extends \BaseController {

    protected function testDb() {
        DB::select('select count(*) from users');  # This throws an exception if it can't connect.
    }

    protected function testRedis() {
        $redis = Redis::connection();
        $redis->set('healthtest', '1');
        $redis->get('healthtest');
    }

    function db() {
        $this->testDb();
        return "OK";
    }

    function redis() {
        $this->testRedis();
        return "OK";
    }

    function all() {
        $this->testRedis();
        $this->testDb();
        return "OK1";
    }

}

