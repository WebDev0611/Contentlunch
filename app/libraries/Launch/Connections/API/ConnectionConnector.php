<?php namespace Launch\Connections\API;

use Illuminate\Support\Facades\Response;
use GuestCollaborator;

class ConnectionConnector
{
    /**
     * If a provider isn't just first-letter capatilized of 
     * provider from DB, then we put that exception in this map
     * @var array
     */
    private static $map = [
        'linkedin' => 'LinkedIn',

        // i.e. we don't need to put Twitter here cuz it does
        // 'twitter' => 'Twitter' automatically
    ];

    /**
     * Takes a provider (from DB) and the $data returned from 
     * $accountConnection->show($accountID, $connectionID);
     * to load the correct API.
     * @param  string                   $provider  e.g. 'twitter', 'linkedin', etc
     * @param  array|AccountConnection  $data      An array/model as returned from 
     *                                             $accountConnection->show($accountID, $connectionID);
     * @return mixed                               A specific instance of an API wrapper depending on the provider 
     */
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

    /**
     * Abstracts away some boilerplate logic needed for every requests.
     * @param  string  $error  Error message
     * @param  integer $status Optional status code. 400 is default and 401s are turned to 
     *                         400s so the user isn't logged out of the ContentLaunch system
     * @return json
     */
    static function responseError($error = 'Unknown error.', $status = 400)
    {
        // a 401 will log the user out of CL. We don't want a 401 from
        // a connection to log out our user out of the CL system!
        if (!$status || $status == 401) $status = 400;

        if (!$error) $error = 'Unknown error.';

        return Response::json(['errors' => [$error]], $status);
    }

    /**
     * Creates a guest collaborator in the database
     * @param  array  $guest  array with all the required fields of a guest collaborator:
     *                        connection_user_id
     *                        access_code
     *                        name
     *                        connection_id
     *                        content_id
     * @return boolean        true on success
     */
    static function createGuestCollaborator($guest)
    {
        // I was originally going to have some logic here, 
        // but it ended up needed to go into each API wrapper
        GuestCollaborator::create($guest);
    }

    /**
     * Generates a unique access code based on the connection_user_id
     * @param  string  $id  connection_user_id (e.g. the user's ID at Twitter)
     * @return string       a unique access code
     */
    static function makeAccessCode($id)
    {
        return uniqid($id, true);
    }

    /**
     * Generates the URL with the $accessCode for to share
     * @param  string  $accessCode  an access code generated from ConnectionConnector::makeAccessCode
     * @return string               the share URL
     */
    static function makeShareLink($accessCode)
    {
        $protocol = 'http' . (!empty($_SERVER['HTTPS']) ? 's' : '');
        return "{$protocol}://{$_SERVER['HTTP_HOST']}/collaborate/guest/{$accessCode}";
    }
}
