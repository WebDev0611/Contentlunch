<?php
namespace oAuth\API;

use Config;
use Crypt;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class MailchimpAuth {

    protected $authorizationEndpoint = 'https://login.mailchimp.com/oauth2/authorize';
    protected $accessTokenEndpoint = 'https://login.mailchimp.com/oauth2/token';
    protected $metadataEndpoint = 'https://login.mailchimp.com/oauth2/metadata';
    protected $client;

    public function __construct()
    {
        $this->client = new Client;
        $this->client_id = Config::get('services.mailchimp.client_id');
        $this->redirect = Config::get('services.mailchimp.redirect');
        $this->client_secret = Config::get('services.mailchimp.client_secret');
    }

    public function getAuthorizationUrl()
    {
        $data = [
            'client_id' => $this->client_id,
            'redirect_uri' => $this->redirect,
            'response_type' => 'code'
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