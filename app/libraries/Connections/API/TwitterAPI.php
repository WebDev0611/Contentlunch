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
            $payload = [ 'status' => $message ];

            if ($this->content->attachments) {
                $payload['media_ids'] = $this->content->attachments
                    ->pluck('twitter_media_id_string')
                    ->filter()
                    ->slice(0, 4)
                    ->implode(',');
            }

            Twitter::postTweet($payload);
        }
        catch (Exception $e) {

        }
    }

    /**
     * Uploads attachments. Right now working only for images.
     *
     * @return void
     */
    public function uploadAttachments()
    {
        $attachments = $this->content->attachments;

        foreach ($attachments as $attachment) {

            if (($attachment->twitter_media_id_string) ||
                ($attachment->type != 'image'))
            {
                continue;
            }

            $base64file = $this->base64file($attachment->filename);

            try {

                $response = Twitter::uploadMedia([ 'media_data' => $base64file ]);
                $attachment->twitter_media_id_string = $response->media_id_string;
                $attachment->save();

            } catch (Exception $e) {
                continue;
            }
        }
    }

    private function base64file($url)
    {
        return base64_encode(file_get_contents($url));
    }
}