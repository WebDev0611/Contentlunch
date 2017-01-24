<?php

namespace Connections\API;

use GuzzleHttp\Client;
use Storage;
use Exception;


class HubspotAPI {

    protected $configKey = 'hubspot';

    protected $base_url = 'https://api.hubapi.com/content/api/v2/';

    public function __construct($content = null, $connection = null)
    {
        $this->client = null;
        $this->content = $content;
        $this->connection = $connection ? $connection : $this->content->connection;
    }

    /*
     * TODO
     * 
    public function createPost()
    {
        $response = [
            'success' => false,
            'response' => []
        ];

        try {
            $options = $this->createOptionsAndHeaderData();
            $url = $this->baseUrl() . '/posts/new';

            $context = stream_context_create($options);
            $apiResponse = file_get_contents($url, false, $context);

            $response = [
                'success' => true,
                'response' => json_decode($apiResponse),
            ];

        } catch (ClientException $e) {
            $responseBody = json_decode($e->getResponse()->getBody(true));
            $response['success'] = false;
            $response['error'] = $responseBody->message;
        }

        return $response;
    }
    */

}