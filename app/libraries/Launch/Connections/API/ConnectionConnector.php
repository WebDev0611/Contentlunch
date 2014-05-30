<?php namespace Launch\Connections\API;

use Illuminate\Support\Facades\Response;
use Models\GuestCollaborator;

class ConnectionConnector
{
    // if a provider isn't just first-letter capatilized of 
    // provider from DB, then we put that exception in this map
    private static $map = [
        'linkedin' => 'LinkedIn'
    ];

    static function loadAPI($provider, $data)
    {
        if (@self::$map[$provider]) $provider = self::$map[$provider];
        else $provider = ucfirst($provider);
        $provider = "Launch\Connections\API\\{$provider}API";

        if (!is_array($data)) 
            $data = $data->toArray();

        if (is_string($data['settings'])) 
            $data['settings'] = unserialize($data['settings']);

        return new $provider($data);
    }

    static function responseError($error = 'Unknown error.', $status = 400)
    {
        // a 401 will log the user out of CL. We don't want a 401 from
        // a connection to log out our user out of the CL system!
        if (!$status || $status == 401) $status = 400;

        if (!$error) $error = 'Unknown error.';

        return Response::json(['errors' => [$error]], $status);
    }

    static function createGuestCollaborator($guest)
    {
        GuestCollaborator::create($guest);
    }
}
