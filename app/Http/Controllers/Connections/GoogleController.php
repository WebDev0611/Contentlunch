<?php

namespace App\Http\Controllers\Connections;


use Illuminate\Http\Request;
use oAuth\API\GoogleAnalyticsAuth;
use oAuth\API\GoogleDriveAuth;

class GoogleController extends BaseConnectionController {

    protected $service;

    public function callback (Request $request, $service)
    {
        if ($error = $request->has('error')) {
            $this->cleanSessionConnection();

            return $this->redirectWithError(json_encode($request->input('error')));
        }

        if(!$request->has('code')) {
            return $this->redirectWithError('There was an error with your authentication, please try again');
        }

        $this->service = $service;
        $token = $this->codeForToken($request->input('code'));

        if (collect($token)->has('error')) {
            return $this->redirectWithError('There was an error with your authentication, please try again');
        }

        switch ($this->service) {
            case 'drive':
                $providerSlug = 'google-drive';
                break;
            case 'analytics':
                $providerSlug = 'google-analytics';
                break;
            default:
                return $this->redirectWithError('There was an error with callback, please try again');
        }

        $connection = $this->saveConnection($token, $providerSlug);

        return $this->redirectWithSuccess("You've successfully connected to Google.");
    }

    public function codeForToken($code)
    {
        switch ($this->service) {
            case 'drive':
                $auth = new GoogleDriveAuth();
                break;
            case 'analytics':
                $auth = new GoogleAnalyticsAuth();
                break;
        }

        $auth->client->authenticate($code);

        return $auth->client->getAccessToken();
    }

    public function tokenPostData($code)
    {
        $auth = new GoogleDriveAuth();

        return [
            'client_id' => $auth->client->getClientId(),
            'redirect_uri' =>  $auth->client->getRedirectUri(),
            'client_secret' =>  $auth->client->getClientSecret(),
            'code' => $code,
            'grant_type' => 'authorization_code'
        ];
    }
}