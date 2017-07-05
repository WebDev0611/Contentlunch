<?php

namespace App\Http\Controllers\Connections;

use App\Account;
use App\Connection;
use App\Provider;
use Connections\API\GoogleAnalyticsAPI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use oAuth\API\GoogleAnalyticsAuth;
use oAuth\API\GoogleDriveAuth;

class GoogleController extends BaseConnectionController {

    protected $auth;
    protected $providerSlug;

    public function analytics ()
    {
        $conn = Account::selectedAccount()
            ->connections()
            ->where('provider_id', Provider::whereSlug('google-analytics')->first()->id)
            ->active()
            ->first();

        if (!$conn) {
            return response('No connection');
        }

        $api = new GoogleAnalyticsAPI(null, $conn);

        //TODO: this is some test data, replace it
        $data = $api->getProfileData('51602619');

        echo 'Displaying data for profile: ' . $data['modelData']['profileInfo']['profileName'] . '<br>';
        echo 'Date range: ' . $data['modelData']['query']['start-date'] . ' - ' . $data['modelData']['query']['end-date'] .  '<br>';

        return 'ok';
    }

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

        try {
            $token = $this->codeForToken($request->input('code'));
        } catch (\Exception $e) {
            return $this->redirectWithError('There was an error with callback, please try again');
        }

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

    protected function loadServiceProperties ($service)
    {
        switch ($service) {
            case 'drive':
                $this->providerSlug = 'google-drive';
                $this->auth = new GoogleDriveAuth();
                break;
            case 'analytics':
                $this->providerSlug = 'google-analytics';
                $this->auth = new GoogleAnalyticsAuth();
                break;
        }
    }
}