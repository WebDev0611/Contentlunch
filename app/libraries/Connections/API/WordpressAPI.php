<?php

namespace Connections\API;

use GuzzleHttp\Client;
use Storage;
use Exception;

class WordPressAPI
{
    protected $base_url = 'https://public-api.wordpress.com/rest/v1.2/';
    protected $connection;
    protected $content;

    public function __construct($content = null, $connection = null, $link = null)
    {
        $this->content = $content;
        $this->connection = $this->getConnection($connection);
        $this->client = new Client([ 'base_uri' => $this->baseUrl() ]);
    }

    protected function getConnection($connection = null)
    {
        if (!$connection) {
            $connection = $this->content ? $this->content->connection : null;
        }

        return $connection;
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
        if($this->content->type === "trend"){
            return [];
        }

        return $this->content->tags->map(function($tag) {
                return trim($tag->tag);
            })
            ->toArray();
    }

    private function postData()
    {
        if ($mediaUrls = $this->getMediaUrls()) {
            $featuredImage = $mediaUrls[0];

            $mediaData = [
                'media_urls' => $mediaUrls,
                'featured_image' => $featuredImage,
            ];
        } else {
            $mediaData = [];
        }

        return array_merge($mediaData, [
            'title' => $this->content->title,
            'content' => $this->content->body,
            'tags' => $this->tags(),
            'status' => 'draft',
        ]);
    }

    protected function createOptionsAndHeaderData()
    {
        return [
            'http' => [
                'method' => 'POST',
                'ignore_errors' => true,
                'header' => $this->headers(),
                'content' => http_build_query($this->postData()),
            ],
        ];
    }

    public function createPost()
    {
        $response = [
            'success' => false,
            'response' => []
        ];

        $post_id = null;
        $publish_url = null;

        try {
            $options = $this->createOptionsAndHeaderData();
            $url = $this->baseUrl() . '/posts/new';

            $context = stream_context_create($options);
            $apiResponse = file_get_contents($url, false, $context);

            $responseData = json_decode($apiResponse);

            $post_id = $responseData->ID;

            $publish_url = str_replace("%postname%",
                $responseData->other_URLs->suggested_slug,
                $responseData->other_URLs->permalink_URL);

            $response = [
                'success' => true,
                'response' => $responseData,
            ];

        } catch (ClientException $e) {
            $responseBody = json_decode($e->getResponse()->getBody(true));
            $response['success'] = false;
            $response['error'] = $responseBody->message;
        }

        $this->content->wordpress_post_id =  $post_id;
        $this->content->publish_url =  $publish_url;
        $this->content->setPublished();

        $this->content->save();

        return $response;
    }

    public function blogInfo($blogUrl)
    {
        $response = $this->client->get('sites/' . $blogUrl);

        return json_decode((string) $response->getBody());
    }

    private function getMediaUrls()
    {
        if($this->content->type === "trend"){
            return [$this->content->image];
        }

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
        $mediaUrls = $this->getMediaUrls()->toArray();
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
