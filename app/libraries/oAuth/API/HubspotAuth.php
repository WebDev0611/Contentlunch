<?php

namespace oAuth\API;

use GuzzleHttp\Client;
use Config;
use Crypt;

class HubspotAuth {

    protected $authorizationEndpoint = 'https://app.hubspot.com/oauth/authorize';
    protected $accessTokenEndpoint = 'https://api.hubapi.com/oauth/v1/token';
    protected $client;

    public function __construct () {
        $this->client = new Client;
        $this->client_id = Config::get('services.hubspot.client_id');
        $this->redirect = Config::get('services.hubspot.redirect');
        $this->client_secret = Config::get('services.hubspot.client_secret');
    }

    public function getAuthorizationUrl ($scope) {
        $data = [
            'client_id' => $this->client_id,
            'redirect_uri' => $this->redirect,
            'scope' => implode(" ", $scope)
        ];

        $params = collect($data)
            ->map(function ($value, $key) {
                return "$key=$value";
            })
            ->implode('&');

        return $this->authorizationEndpoint . '?' . $params;
    }

    public function codeForToken ($code) {
        $curl = curl_init($this->accessTokenEndpoint);
        curl_setopt($curl, CURLOPT_POST, true);
         curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($this->tokenPostData($code)));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/x-www-form-urlencoded;charset=utf-8'
        ));

        $auth = curl_exec($curl);

        return json_decode($auth);
    }

    public function tokenPostData ($code) {
        return [
            'client_id' => $this->client_id,
            'redirect_uri' => $this->redirect,
            'client_secret' => $this->client_secret,
            'code' => $code,
            'grant_type' => 'authorization_code'
        ];
    }

}