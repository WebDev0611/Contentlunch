<?php

namespace Connections\API;

use GuzzleHttp\Client;
use Storage;
use Exception;

class WordPressAPI
{
    // - dunno if needed
    protected $configKey = 'wordpress';

    protected $base_url = 'https://public-api.wordpress.com/rest/v1.2/';

    public function __construct($content = null, $connection = null)
    {
        $this->content = $content;
        $this->connection = $connection ?
            $connection :
            ($this->content ? $this->content->connection : null);

        $this->client = new Client([ 'base_uri' => $this->baseUrl() ]);

        // if ($content) {
        //     $this->token = (new \oAuth\API\WordPressAuth)->getToken($content);
        // }
        // $this->domain = $content->connection->getSettings()->url;
    }

    public function baseUrl()
    {
        $settings = $this->settings();
        $url = $settings ?
            $this->base_url . 'sites/' . $settings->blog_url :
            $this->base_url . 'sites/';

        return rtrim($url, '/');
    }

    public function settings()
    {
        return $this->connection ?
            $this->connection->getSettings() :
            null;
    }

    public function headers()
    {
        return [
            0 => 'authorization: Bearer ' . $this->settings()->access_token,
            1 => 'Content-Type: application/x-www-form-urlencoded',
        ];
    }

    private function tags()
    {
        return $this->content->tags->map(function($tag) {
                return trim($tag->tag);
            })
            ->toArray();
    }

    private function postData()
    {
        return [
            'title' => $this->content->title,
            'content' => $this->content->body,
            'tags' => $this->tags(),
            'media_urls' => $this->getMediaUrls()
        ];
    }


    public function createPost()
    {
        // - standardize return
        $response = ['success' => false, 'response' => []];

        try {
            // - Create Options and Header Data
            $options = [
                'http' => [
                    'method' => 'POST',
                    'ignore_errors' => true,
                    'header' => $this->headers(),
                    'content' => http_build_query($this->postData()),
                ],
            ];

            // REST API url
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

    public function blogInfo($blogUrl)
    {
        $response = $this->client->get($blogUrl);

        return json_decode((string) $response->getBody());
    }

    private function getMediaUrls()
    {
        return $this->content
            ->attachments
            ->where('type', 'image')
            ->pluck('filename')
            ->toArray();
    }

    private function getMediaUploadUrl()
    {
        return 'https://public-api.wordpress.com/rest/v1.1/sites/' . $this->settings()->blog_url . '/media/new';
    }

    public function uploadAttachments()
    {
        $mediaUrls = $this->getMediaUrls();
        $mediaUploadUrl = $this->getMediaUploadUrl();

        $response = ['success' => false, 'response' => []];

        try {
            // - Create Options and Header Data
            $options = [
                'http' => [
                    'method' => 'POST',
                    'ignore_errors' => false,
                    'header' => $this->headers(),
                    'content' => http_build_query([
                        'media_urls' => $mediaUrls
                    ]),
                ],
            ];

            $context = stream_context_create($options);
            $apiResponse = file_get_contents($mediaUploadUrl, false, $context);

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
}
