<?php

namespace App\Http\Controllers\Connections;

use App\Http\Controllers\Controller;
use App\Connection;
use Session;
use Redirect;

abstract class BaseConnectionController extends Controller
{

    public function getSessionConnection()
    {
        $connection_data = Session::get('connection_data');
        $connection = Connection::find($connection_data['connection_id']);

        return $connection;
    }

    public function cleanSessionConnection()
    {
        Session::forget('connection_data');
    }

    protected function redirectRoute()
    {
        $redirectUrl = Session::get('redirect_route');
        Session::forget('redirect_route');

        return $redirectUrl ? $redirectUrl : 'connectionIndex';
    }

}
