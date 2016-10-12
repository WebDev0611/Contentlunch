<?php

namespace App\Http\Controllers\Connections;

use App\Http\Controllers\Controller;
use App\Connection;
use Session;

abstract class BaseConnectionController extends Controller
{

    public function getSessionConnection()
    {
        $connection_data = Session::get('connection_data');
        $connection = Connection::find($connection_data['connection_id']);

        return $connection;
    }

}
