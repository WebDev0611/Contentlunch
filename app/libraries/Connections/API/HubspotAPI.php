<?php

namespace Connections\API;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Input;
use oAuth\API\HubspotAuth;
use SevenShores\Hubspot\Factory as HubspotFactory;
use Exception;


class HubspotAPI {

    protected $configKey = 'hubspot';
    protected $base_url = 'https://api.hubapi.com';
    private $blog;

    public function __construct ($content = null, $connection = null)
    {
        $this->client = null;
        $this->content = $content;
        $this->connection = $connection ? $connection : $this->content->connection;
    }

    public function createPost ()
    {
        $hubspot = $this->createHubspotFactoryInstance();

        // Response array
        $response = [
            'success'  => false,
            'response' => []
        ];

        // Get the blog we're posting to
        // Note: user can have multiple blogs, but for now we'll get the first one
        $blogs = ($hubspot->blogs()->all()->getBody()->getContents());
        $this->blog = json_decode($blogs)->objects[0];

        try {
            $createResponse = $hubspot->blogPosts()->create($this->prepareBlogPostData());

            if ($createResponse->getStatusCode() == '201') {

                // $blogPostId = json_decode($createResponse->getBody()->getContents())->id;
                // $hubspot->blogPosts()->publishAction($blogPostId, 'schedule-publish');

                $response = [
                    'success'  => true,
                    'response' => json_encode($createResponse),
                ];

                $this->content->setPublished();
            }
        } catch (ClientException $e) {
            $responseBody = json_decode($e->getResponse()->getBody());
            $response['success'] = false;
            $response['error'] = $responseBody->message;
        }

        return $response;
    }

    public function createPage ()
    {
        $hubspot = $this->createHubspotFactoryInstance();

        // Response array
        $response = [
            'success'  => false,
            'response' => []
        ];

        try {
            $createResponse = $hubspot->pages()->create($this->preparePagePostData());

            if ($createResponse->getStatusCode() == '201') {
                $response = [
                    'success'  => true,
                    'response' => json_encode($createResponse),
                ];

                $this->content->setPublished();
            }
        } catch (ClientException $e) {
            $responseBody = json_decode($e->getResponse()->getBody());
            $response['success'] = false;
            $response['error'] = $responseBody->message;
        }

        return $response;
    }


    public function saveConnectionSettings ($jsonEncodedSettings)
    {
        $this->connection->settings = $jsonEncodedSettings;
        $this->connection->save();
    }

    private function createHubspotFactoryInstance ()
    {
        // Refresh connection token
        $auth = new HubspotAuth();
        $settings = json_decode($this->connection->settings);

        $refreshedSettings = $auth->refreshToken($settings->refresh_token);
        $this->saveConnectionSettings(json_encode($refreshedSettings));

        // New Hubspot Factory instance
        $hubspot = new HubspotFactory([
            'key'      => $settings->access_token,
            'oauth2'   => true,
            'base_url' => $this->base_url
        ], null,
            ['http_errors' => getenv('APP_ENV') == 'local' ? true : false],
            false
        );

        return $hubspot;
    }

    /**
     * Prepares input data for posting to Hubspot Blog
     * @return array
     */
    private function prepareBlogPostData ()
    {

        $isImageSet = Input::has('images');
        $featuredImage = $isImageSet ? $this->getMediaUrls()->shift() : '';

        return [
            'content_group_id'   => $this->blog->id,
            'name'               => $this->content->title,
            'post_body'          => $this->content->body,
            //'keywords' => $this->content->meta_keywords, // Keywords are not supported on test portal
            'meta_description'   => $this->content->meta_description,
            'featured_image'     => $featuredImage,
            'use_featured_image' => $isImageSet,
            //'publish_date' => '' // TODO: schedule date for posting
        ];
    }

    /**
     * Prepares input data for posting to Hubspot Page
     * @return array
     */
    private function preparePagePostData ()
    {
        return [
            'name'             => $this->content->title,
            'footer_html'      => '<p>footer html</p>',
            'head_html'        => '<h1>head html</h1>',
            'html_title'       => $this->content->title,
            'is_draft'         => 'true',
            'meta_description' => $this->content->meta_description,
            'meta_keywords'    => $this->content->meta_keywords,
            'template_path'    => 'hubspot_default/landing_page/basic_with_form/2_col_form_left.html',
            'widgets'          => [
                'right_column' => [
                    'body' => [
                        'html' => $this->content->body
                    ]
                ]
            ]
        ];
    }

    private function getMediaUrls ()
    {
        return $this->content
            ->attachments
            ->where('type', 'image')
            ->pluck('filename');
    }

}