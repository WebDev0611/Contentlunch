<?php

namespace oAuth\API;

use Google_Client;
use Google_Http_MediaFileUpload;
use Google_Service_Drive;
use Illuminate\Support\Facades\Config;

class GoogleDriveAuth {

    public $client;

    public function __construct ()
    {
        $this->client = new Google_Client();
        $this->client->setClientId(Config::get('services.google-drive.client_id'));
        $this->client->setClientSecret(Config::get('services.google-drive.client_secret'));
        $this->client->setRedirectUri(Config::get('services.google-drive.redirect'));
    }

    public function getAuthorizationUrl ()
    {
        $this->client->addScope("https://www.googleapis.com/auth/drive");

        return $this->client->createAuthUrl();
    }
}