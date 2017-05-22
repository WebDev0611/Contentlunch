<?php

namespace Connections\API;

use oAuth\API\GoogleAnalyticsAuth;

class GoogleAnalyticsAPI {

    protected $auth;

    protected $analyticsService;


    public function __construct ($content = null, $connection = null)
    {
        $this->client = null;
        $this->content = $content;
        $this->connection = $connection ? $connection : $this->content->connection;
        $this->auth = new GoogleAnalyticsAuth();

        $this->refreshConnection();
        $this->analyticsService = new \Google_Service_Analytics($this->client);
    }

    public function getFirstProfile ()
    {
        // Get the user's first view (profile).

        // Get the list of accounts for the authorized user.
        $accounts = $this->analyticsService->management_accounts->listManagementAccounts();

        if (count($accounts->getItems()) > 0) {
            $items = $accounts->getItems();
            $firstAccountId = $items[0]->getId();

            // Get the list of properties for the authorized user.
            $properties = $this->analyticsService->management_webproperties
                ->listManagementWebproperties($firstAccountId);

            if (count($properties->getItems()) > 0) {
                $items = $properties->getItems();
                $firstPropertyId = $items[0]->getId();

                // Get the list of views (profiles) for the authorized user.
                $profiles = $this->analyticsService->management_profiles
                    ->listManagementProfiles($firstAccountId, $firstPropertyId);

                if (count($profiles->getItems()) > 0) {
                    $items = $profiles->getItems();

                    // Return the first view (profile) ID.
                    //return $items[0]->getId();
                    return $items[0];

                }
                else {
                    throw new \Exception('No views (profiles) found for this user.');
                }
            }
            else {
                throw new \Exception('No properties found for this user.');
            }
        }
        else {
            throw new \Exception('No accounts found for this user.');
        }
    }

    public function getProfileData ($analyticsViewId, $startDate, $endDate)
    {
        $metrics = [
            'ga:sessions',
            'ga:pageviews',
            'ga:users',
            'ga:avgSessionDuration',
            'ga:bounceRate',
            'ga:organicSearches',
            //'ga:hits',
            'ga:pageLoadTime',
            'ga:socialInteractions'
        ];

        $res = $this->analyticsService->data_ga->get(
            'ga:' . $analyticsViewId,
            $startDate,
            $endDate,
            implode(',', $metrics)
        );

        return $res['totalsForAllResults'];
    }

    private function refreshConnection ()
    {
        $settings = json_decode($this->connection->settings);

        if (time() > ($settings->created + $settings->expires_in)) {
            $refreshedSettings = $this->auth->refreshToken($settings->refresh_token);
            $this->connection->settings = json_encode($refreshedSettings);
            $this->connection->save();
        }
        else {
            $this->auth->client->setAccessToken($settings->access_token);
        }

        $this->client = $this->auth->client;
    }
}