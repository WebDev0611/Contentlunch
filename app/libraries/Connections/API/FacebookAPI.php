<?php

namespace Connections\API;

use Illuminate\Support\Facades\Config;
use Facebook\Facebook;
use Exception;

class FacebookAPI
{
    protected $client;
    protected $content;
    protected $connection;

    public function __construct($content, $connection = null)
    {
        $this->content = $content;
        $this->connection = $connection ? $connection : $this->content->connection;
        $this->client = $this->getClient();
    }

    public function getClient()
    {
        return new Facebook([
            'app_id' => Config::get('services.facebook.client_id'), // content launch app id
            'app_secret' => Config::get('services.facebook.client_secret'), // content launch secret id
            'default_graph_version' => 'v2.5',
            'default_access_token' => $this->settings()->page_token,
        ]);
    }

    public function createPost()
    {
        try {
            $facebookResponse = $this->client->post($this->createPostUrl(), $this->payload());

            $response = [
                'success' => true,
                'response' => json_decode($facebookResponse->getGraphNode()),
            ];

        } catch (\Facebook\Exceptions\FacebookResponseException $e) {
            $response = $this->errorResponse($e, 'Graph returned an error');
        } catch (\Facebook\Exceptions\FacebookSDKException $e) {
            $response = $this->errorResponse($e, 'Facebook SDK returned and error');
        }

        return $response;
    }

    protected function payload()
    {
        $attachmentIDs = $this->uploadAttachments()
            ->filter(function($response) { return $response['success']; })
            ->map(function($response) { return $response['response']->id; });

        $payload = [
            'message' => $this->formatPost(),
            'link' => $this->content->link ? $this->content->link : ""
        ];

        if (!$attachmentIDs->isEmpty()) {
            $payload['object_attachment'] = $attachmentIDs[0];
        }

        return $payload;
    }

    private function formatPost()
    {
        if($this->content->type == "trend"){
            return strip_tags($this->content->body);
        }

        $message = strip_tags($this->content->body);

        if ($this->content->title) {
            $message = strip_tags($this->content->title).' - '.$message;
        }

        return $message;
    }

    public function uploadAttachments()
    {
        $mediaUrls = $this->getMediaUrls();

        $responses = collect($mediaUrls)->map(function($imageUrl) {

            $payload = [ 'source' => $this->client->fileToUpload($imageUrl) ];

            try {

                $facebookResponse = $this->client->post($this->uploadUrl(), $payload);

                $response = [
                    'success' => true,
                    'response' => json_decode($facebookResponse->getGraphNode()),
                ];

            } catch (\Facebook\Exceptions\FacebookResponseException $e) {
                $response = $this->errorResponse($e, 'Graph returned an error');
            } catch (\Facebook\Exceptions\FacebookSDKException $e) {
                $response = $this->errorResponse($e, 'Facebook SDK returned and error');
            }

            return $response;
        });

        return $responses;
    }

    private function getMediaUrls()
    {
        if($this->content->type == "trend"){
            return [];
        }

        return $this->content
            ->attachments
            ->where('type', 'image')
            ->pluck('filename')
            ->toArray();
    }

    private function errorResponse($exception, $error)
    {
        return [
            'success' => false,
            'error' => $error . ': ' . json_decode($exception->getMessage()),
            'response' => []
        ];
    }

    private function uploadUrl()
    {
        return '/' . $this->settings()->page_id . '/photos';
    }

    private function createPostUrl()
    {
        return '/' . $this->settings()->page_id . '/feed';
    }

    protected function settings()
    {
        return $this->connection->getSettings();
    }

    /* public function getUserToken($provider)
     {
         $user = $this->getUser($provider);
         return $user->token;
     }

     public function getAccounts()
     {
         $client = $this->getClient();
         // Get Account List
         $response = $client->get('/me/accounts');
         return  $response->getGraphEdge();
     }

     public function getLongLivedAccessToken($user)
     {
         $client = $this->getClient();
         $oAuth2Client = $client->getOAuth2Client();
         $accessToken = $oAuth2Client->getLongLivedAccessToken($user->token);
         //$dateToken = $accessToken->getExpiresAt();
         return (string)  $accessToken;
     }*/

}
