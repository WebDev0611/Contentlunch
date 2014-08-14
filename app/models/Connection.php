<?php

use LaravelBook\Ardent\Ardent;

class Connection extends Ardent {

    protected $table = 'connections';

    public function account_connections()
    {
        return $this->belongsToMany('AccountConnection', 'content_account_connections', 'content_id', 'account_connection_id')
            ->withTimestamps()
            ->with('connection');
    }

}