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


class HubspotAPI
{
    protected $configKey = 'hubspot';
    protected $base_url = 'https://api.hubapi.com';
    private $blog;

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

        // Response array
        $response = [
            'success' => false,
            'response' => []
        ];
        
        // Get the blog we're posting to
        // Note: user can have multiple blogs, but for now we'll get the first one
        $blogs = ($hubspot->blogs()->all()->getBody()->getContents());
        $this->blog = json_decode($blogs)->objects[0];

        try {
            $createResponse = $hubspot->blogPosts()->create($this->preparePostingData());

            if ($createResponse->getStatusCode() == '201') {

                // TODO: If we scheduled the date for publishing, we have to make another call to the API
                // $blogPostId = json_decode($createResponse->getBody()->getContents())->id;
                // $hubspot->blogPosts()->publishAction($blogPostId, 'schedule-publish');

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

        $this->content->setPublished();

        return $response;
    }

    public function saveConnectionSettings ($jsonEncodedSettings) {
        $this->connection->settings = $jsonEncodedSettings;
        $this->connection->save();
    }

    /**
     * Prepares input data for posting to Hubspot
     * @return array
     */
    private function preparePostingData () {

        $isImageSet = Input::has('images');
        $featuredImage = $isImageSet ? $this->getMediaUrls()->shift() : '';

        return [
            'content_group_id' => $this->blog->id,
            'name' => $this->content->title,
            'post_body' => $this->content->body,
            //'keywords' => $this->content->meta_keywords, // Keywords are not supported on test portal
            'meta_description' => $this->content->meta_description,
            'featured_image' => $featuredImage,
            'use_featured_image' => $isImageSet,
            //'publish_date' => '' // TODO: schedule date for posting
        ];
    }

    private function getMediaUrls () {
        return $this->content
            ->attachments
            ->where('type', 'image')
            ->pluck('filename');
    }

}