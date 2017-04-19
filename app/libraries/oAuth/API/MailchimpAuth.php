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
    protected $client;

    public function __construct()
    {
        $this->client = new Client;
        $this->client_id = Config::get('services.mailchimp.client_id');
        $this->redirect = Config::get('services.mailchimp.redirect');
        $this->client_secret = Config::get('services.mailchimp.client_secret');
    }

}