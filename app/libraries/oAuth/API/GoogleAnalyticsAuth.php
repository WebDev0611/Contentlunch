<?php

namespace oAuth\API;

use Google_Service_Analytics;

class GoogleAnalyticsAuth extends GoogleAuth {

    public function __construct ()
    {
        parent::__construct();
        $this->client->addScope(Google_Service_Analytics::ANALYTICS_READONLY);
    }
}