<?php

namespace oAuth\API;

use Config;
use Crypt;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class WordPressAuth
{
    protected $authorizationEndpoint = 'https://public-api.wordpress.com/oauth2/authorize';
    protected $accessTokenEndpoint = 'https://public-api.wordpress.com/oauth2/token';
    protected $client;

    public function __construct()
    {
        $this->client = new Client;
        $this->client_id = Config::get('services.wordpress.client_id');
        $this->redirect = Config::get('services.wordpress.redirect');
        $this->client_secret = Config::get('services.wordpress.client_secret');
    }

    public function getAuthorizationUrl()
    {
        $data = [
            'client_id' => $this->client_id,
            'redirect_uri' => $this->redirect,
            'response_type' => 'code',
            'scope' => 'global'
        ];

        $params = collect($data)
            ->map(function ($value, $key) { return "$key=$value"; })
            ->implode('&');

        return $this->authorizationEndpoint . '?' . $params;
    }

    public function codeForToken($code)
    {
        $curl = curl_init($this->accessTokenEndpoint);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $this->tokenPostData($code));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $auth = curl_exec($curl);

        return json_decode($auth);
    }

    public function tokenPostData($code)
    {
        return [
            'client_id' => $this->client_id,
            'redirect_uri' => $this->redirect,
            'client_secret' => $this->client_secret,
            'code' => $code,
            'grant_type' => 'authorization_code'
        ];
    }

    public function getToken($content)
    {
        $connectionSettings = $content->connection->getSettings();

        $curl = curl_init($this->accessTokenEndpoint);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, [
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
            'grant_type' => 'password',
            'username' => $connectionSettings->username,
            'password' =>  Crypt::decrypt($connectionSettings->password),
        ]);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $auth = curl_exec($curl);
        $auth = json_decode($auth);
        $access_key = $auth->access_token;

        return $access_key;
    }
}
