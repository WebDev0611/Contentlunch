<?php

namespace oAuth\API;

use Google_Service_Drive;
use Illuminate\Support\Facades\Config;

class GoogleDriveAuth extends GoogleAuth {

    public function __construct ()
    {
        parent::__construct();

        $this->client->setRedirectUri(Config::get('services.google-drive.redirect'));
        $this->client->addScope(Google_Service_Drive::DRIVE);
    }
}