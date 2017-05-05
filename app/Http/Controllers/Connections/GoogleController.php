<?php

namespace App\Http\Controllers\Connections;

use Illuminate\Http\Request;
use oAuth\API\GoogleAnalyticsAuth;
use oAuth\API\GoogleDriveAuth;

class GoogleController extends BaseConnectionController {

    protected $auth;
    protected $providerSlug;

    public function callback (Request $request, $service)
    {
        if ($error = $request->has('error')) {
            $this->cleanSessionConnection();

            return $this->redirectWithError(json_encode($request->input('error')));
        }

        if (!$request->has('code')) {
            return $this->redirectWithError('There was an error with your authentication, please try again');
        }

        $this->loadServiceProperties($service);
        $token = $this->codeForToken($request->input('code'));

        if (collect($token)->has('error')) {
            return $this->redirectWithError('There was an error with your authentication, please try again');
        }

        $connection = $this->saveConnection($token, $this->providerSlug);

        return $this->redirectWithSuccess("You've successfully connected to Google.");
    }

    public function codeForToken ($code)
    {
        $this->auth->client->authenticate($code);

        return $this->auth->client->getAccessToken();
    }

    protected function loadServiceProperties($service) {
        switch ($service) {
            case 'drive':
                $this->providerSlug = 'google-drive';
                $this->auth = new GoogleDriveAuth();
                break;
            case 'analytics':
                $this->providerSlug = 'google-analytics';
                $this->auth = new GoogleAnalyticsAuth();
                break;
            default:
                return $this->redirectWithError('There was an error with callback, please try again');
        }
    }
}