<?php

namespace Connections\API;


use Artesaos\LinkedIn\Facades\LinkedIn;
use GuzzleHttp\Exception\ClientException;

class LinkedInAPI {

    protected $client;
    protected $content;
    protected $connection;

    public function __construct ($content, $connection = null)
    {
        $this->content = $content;
        $this->connection = $connection ? $connection : $this->content->connection;
        $this->client = null;

        LinkedIn::setAccessToken(json_decode($this->connection->settings)->token);
    }

    public function createPost ()
    {
        try {
            $postResponse = LinkedIn::post('v1/people/~/shares', $this->preparePostingData());

            if (!isset($postResponse['errorCode'])) {
                $response = [
                    'success'  => true,
                    'response' => 'Post published',
                ];

                //$this->content->setPublished();
            }
            else {
                $response = [
                    'success'  => false,
                    'error' => $postResponse['message']
                ];
            }
        } catch (ClientException $e) {
            $responseBody = json_decode($e->getResponse()->getBody());
            $response['success'] = false;
            $response['error'] = $responseBody->message;
        }

        return $response;
    }

    private function preparePostingData ()
    {
        $data = [
            'json' =>
                [
                    'comment'    => strip_tags($this->content->body),
                    'visibility' => [
                        'code' => 'anyone'
                    ]
                ]
        ];

        $images = $this->content->attachments()->where('type', 'image')->get();
        if(count($images) > 0) {
            $firstImage = $images->first();

            $data['json']['content'] = [
                'title'               => $this->content->title . ': attachment',
                //'description'         => 'sample image description',
                'submitted-url'       => $firstImage->filename,
                'submitted-image-url' => $firstImage->filename,
            ];
        }

        return $data;
    }
}