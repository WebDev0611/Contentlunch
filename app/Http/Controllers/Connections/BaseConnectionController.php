<?php

namespace App\Http\Controllers\Connections;

use Auth;
use Session;
use App\Http\Controllers\Controller;
use App\Connection;
use App\Provider;

abstract class BaseConnectionController extends Controller
{
    public function getSessionConnection()
    {
        $connection_data = Session::get('connection_data');
        $connection = Connection::find($connection_data['connection_id']);

        return $connection;
    }

    public function getSessionConnectionMetadata()
    {
        $connectionData = Session::get('connection_data');

        return $connectionData ? $connectionData['meta_data'] : null;
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

    protected function saveConnection(array $settings, $providerSlug)
    {
        $jsonEncodedSettings = json_encode($settings);
        $connection = $this->getSessionConnection();

        if (!$connection) {
            $provider = Provider::findBySlug($providerSlug);
            $connection = Connection::create([
                'name' => $provider->name . ' Connection',
                'active' => true,
                'successful' => true,
                'settings' => $jsonEncodedSettings,
                'provider_id' => $provider->id,
                'user_id' => Auth::user()->id,
            ]);
        } else {
            $connection->settings = $settings;
            $connection->save();
        }

        return $connection;
    }
}
