<?php

namespace Connections\API;

use Exception;
use Twitter;
use Session;
use Log;

class TwitterAPI
{
    public function __construct($content = null, $connection = null)
    {
        $this->client = null;
        $this->content = $content;

        if ($connection) {
            $this->connection = $connection;
        }
        else {
            $this->connection = $this->content->connection;
        }
    }

    private function setupTwitterConnection()
    {
        $settings = $this->connection->getSettings();

        Session::forget('access_token');
        Twitter::reconfig([
            'token' => $settings->oauth_token,
            'secret' => $settings->oauth_token_secret,
            'consumer_key' => env('TWITTER_CONSUMER_KEY'),
            'consumer_secret' => env('TWITTER_CONSUMER_SECRET'),
        ]);
    }

    public function createPost()
    {
        $this->setupTwitterConnection();
        $this->uploadAttachments();

        $message = strip_tags($this->content->body);

        try {
            Twitter::postTweet([ 'status' => $message ]);
        }
        catch (Exception $e) {
            $flashMessage  = "We couldn't post the content to Twitter using the connection [" . $settings->name . "]. ";
            $flashMessage .= "Please make sure the connection is properly configured before trying again.";
        }
    }

    public function uploadAttachments()
    {
        $attachments = $this->content->attachments;

        foreach ($attachments as $attachment) {

            if ($attachment->twitter_media_id_string) {
                continue;
            }
            $base64file = $this->base64file($attachment->filename, $attachment->mime);
            try {
                $response = Twitter::uploadMedia([ 'media_data' => $base64file ]);
            } catch (Exception $e) {

            }
        }
    }

    private function base64file($url, $mimeType)
    {
        $client = new \Guzzle\Http\Client();
        // $response = $client->get($url)->send();

        // return base64_encode($response->getBody());
        return base64_encode(file_get_contents($url));
    }
}