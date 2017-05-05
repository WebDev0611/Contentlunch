<?php

namespace Connections\API;

use oAuth\API\GoogleAnalyticsAuth;

class GoogleAnalyticsAPI {

    protected $auth;

    public function __construct ($content = null, $connection = null)
    {
        $this->client = null;
        $this->content = $content;
        $this->connection = $connection ? $connection : $this->content->connection;
        $this->auth = new GoogleAnalyticsAuth();
    }

    public function analyticsProfile ()
    {
        $this->refreshConnection();

        $analytics = new \Google_Service_Analytics($this->client);
        $profile = $this->getFirstProfileId($analytics);
        return $profile;
    }

    private function getFirstProfileId($analytics) {
        // Get the user's first view (profile) ID.

        // Get the list of accounts for the authorized user.
        $accounts = $analytics->management_accounts->listManagementAccounts();

        if (count($accounts->getItems()) > 0) {
            $items = $accounts->getItems();
            $firstAccountId = $items[0]->getId();

            // Get the list of properties for the authorized user.
            $properties = $analytics->management_webproperties
                ->listManagementWebproperties($firstAccountId);

            if (count($properties->getItems()) > 0) {
                $items = $properties->getItems();
                $firstPropertyId = $items[0]->getId();

                // Get the list of views (profiles) for the authorized user.
                $profiles = $analytics->management_profiles
                    ->listManagementProfiles($firstAccountId, $firstPropertyId);

                if (count($profiles->getItems()) > 0) {
                    $items = $profiles->getItems();

                    // Return the first view (profile) ID.
                    return $items[0]->getId();

                } else {
                    throw new \Exception('No views (profiles) found for this user.');
                }
            } else {
                throw new \Exception('No properties found for this user.');
            }
        } else {
            throw new \Exception('No accounts found for this user.');
        }
    }

    private function refreshConnection ()
    {
        $settings = json_decode($this->connection->settings);

        $refreshedSettings = $this->auth->refreshToken($settings->refresh_token);
        $this->connection->settings = json_encode($refreshedSettings);
        $this->connection->save();

        $this->client = $this->auth->client;
    }
}