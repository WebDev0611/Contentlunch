<?php

namespace oAuth\API;

use Google_Client;
use Illuminate\Support\Facades\Config;

class GoogleAuth {

    public $client;

    public function __construct ()
    {
        $this->client = new Google_Client();
        $this->client->setClientId(Config::get('services.google-drive.client_id'));
        $this->client->setClientSecret(Config::get('services.google-drive.client_secret'));
        $this->client->setRedirectUri(Config::get('services.google-drive.redirect'));
        $this->client->setAccessType('offline');
        $this->client->setApprovalPrompt('force'); // Important for getting refresh token
    }

    public function getAuthorizationUrl ()
    {
        return $this->client->createAuthUrl();
    }

    public function refreshToken ($refreshToken)
    {
        return $this->client->refreshToken($refreshToken);
    }
}