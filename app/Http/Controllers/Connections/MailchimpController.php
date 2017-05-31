<?php

namespace App\Http\Controllers\Connections;

use Connections\API\MailchimpAPI;
use Illuminate\Http\Request;
use oAuth\API\MailchimpAuth;

class MailchimpController extends BaseConnectionController {

    public function __construct (Request $request)
    {
        $this->auth = new MailchimpAuth();
    }

    public function callback (Request $request)
    {
        if ($error = $request->has('error')) {
            $this->cleanSessionConnection();

            return $this->redirectWithError($this->errorMessage($request->input('error')));
        }

        $code = $request->input('code');
        $token = $this->auth->codeForToken($code);

        if (collect($token)->has('error')) {
            $this->cleanSessionConnection();

            return $this->redirectWithError('There was an error with your authentication, please try again');
        }

        $tokenArray = (array)$token;
        $connection = $this->saveConnection($tokenArray, 'mailchimp');

        // Add datacenter to connection settings
        $connSettings = json_decode($connection->settings);
        $connSettings->datacenter = $this->getDatacenter($connection);
        $connection->settings = json_encode($connSettings);
        $connection->save();

        return $this->redirectWithSuccess("You've successfully connected to Mailchimp.");
    }

    public function getContentLists ($connection)
    {
        $mailchimpApi = new MailchimpAPI(null, $connection);
        $response = $mailchimpApi->getLists();

        $mapped = array_map(function ($el) {
            return [
                'id'                  => $el['id'],
                'name'                => $el['name'],
                'contact'             => $el['contact'],
                'campaign_defaults'   => $el['campaign_defaults'],
                'subscribe_url_short' => $el['subscribe_url_short']
            ];
        }, $response['lists']);

        return response()->json($mapped);
    }

    protected function errorMessage (Request $request, $error)
    {
        return $error == 'access_denied' ?
            'You need to authorize ContentLaunch if you want to use the Mailchimp connection' :
            $request->input('error_description');
    }

    private function getDatacenter ($connection)
    {
        $mailchimpApi = new MailchimpAPI(null, $connection);

        $response = $mailchimpApi->getMetadata();

        return $response['dc'];
    }
}
