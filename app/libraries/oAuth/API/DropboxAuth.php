<?php

namespace oAuth\API;

use GuzzleHttp\Client;
use Config;

class DropboxAuth {

    protected $authorizationEndpoint = 'https://www.dropbox.com/1/oauth2/authorize';
    protected $accessTokenEndpoint = 'https://api.dropboxapi.com/1/oauth2/token';
    protected $client;

    public function __construct () {
        $this->client = new Client;
        $this->client_id = Config::get('services.dropbox.client_id');
        $this->redirect = Config::get('services.dropbox.redirect');
        $this->client_secret = Config::get('services.dropbox.client_secret');
        $this->state = str_random(20);
    }

    public function getAuthorizationUrl () {
        $data = [
            'client_id' => $this->client_id,
            'redirect_uri' => $this->redirect,
            'state' => $this->state
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