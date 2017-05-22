<?php

namespace oAuth\API;

use Google_Service_Analytics;
use Illuminate\Support\Facades\Config;

class GoogleAnalyticsAuth extends GoogleAuth {

    public function __construct ()
    {
        parent::__construct();

        $this->client->setRedirectUri(Config::get('services.google-analytics.redirect'));
        $this->client->addScope(Google_Service_Analytics::ANALYTICS);
        $this->client->addScope(Google_Service_Analytics::ANALYTICS_EDIT);
    }
}