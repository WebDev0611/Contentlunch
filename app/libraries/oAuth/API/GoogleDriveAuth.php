<?php

namespace oAuth\API;

use Google_Service_Drive;

class GoogleDriveAuth extends GoogleAuth {

    public function __construct ()
    {
        parent::__construct();
        $this->client->addScope(Google_Service_Drive::DRIVE);
    }
}