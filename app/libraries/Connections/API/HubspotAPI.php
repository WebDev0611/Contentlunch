<?php

namespace Connections\API;

use App\Connection;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use oAuth\API\HubspotAuth;
use SevenShores\Hubspot\Factory as HubspotFactory;
use Storage;
use Exception;


class HubspotAPI {

    protected $configKey = 'hubspot';

    protected $base_url = 'https://api.hubapi.com';

    public function __construct ($content = null, $connection = null) {
        $this->client = null;
        $this->content = $content;
        $this->connection = $connection ? $connection : $this->content->connection;
    }


    public function createPost () {
        // Refresh connection token
        $auth = new HubspotAuth();
        $settings = json_decode($this->connection->settings);

        $refreshedSettings = $auth->refreshToken($settings->refresh_token);
        $this->saveConnectionSettings(json_encode($refreshedSettings));
        
        // New Hubspot Factory instance
        $hubspot = new HubspotFactory([
            'key' => $settings->access_token,
            'oauth2' => true,
            'base_url' => $this->base_url
        ], null,
            ['http_errors' => false],
            false
        );

        // Return array
        $response = [
            'success' => false,
            'response' => []
        ];

        // Get the blog we're posting to
        // Note: user can have multiple blogs, but for now we'll get the first one
        $blogs = ($hubspot->blogs()->all()->getBody()->getContents());
        $blog = json_decode($blogs)->objects[0];

        //var_dump(json_decode($hubspot->blogPosts()->all()->getBody()->getContents()));

        try {
            // TODO: load all posting parameters
            $createResponse = $hubspot->blogPosts()->create([
                'content_group_id' => $blog->id,
                'name' => $this->content->title
            ]);

            // If response status code starts with "2" (e.g. 200) , we're OK
            if (substr($createResponse->getStatusCode(), 0, 1) == '2') {
                $response = [
                    'success' => true,
                    'response' => json_encode($createResponse),
                ];
            }
        } catch (ClientException $e) {
            $responseBody = json_decode($e->getResponse()->getBody(true));
            $response['success'] = false;
            $response['error'] = $responseBody->message;
        }

        return $response;
    }

    public function saveConnectionSettings ($jsonEncodedSettings) {
        $this->connection->settings = $jsonEncodedSettings;
        $this->connection->save();
    }

}