<?php

namespace Connections\API;

use Twitter;
use Session;

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
        $settings = $this->content->connection->getSettings();

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

        $message = strip_tags($this->content->body);

        try {
            Twitter::postTweet([ 'status' => $message ]);
        }
        catch (Exception $e) {
            $flashMessage  = "We couldn't post the content to Twitter using the connection [" . $settings->name . "]. ";
            $flashMessage .= "Please make sure the connection is properly configured before trying again.";
        }
    }
}