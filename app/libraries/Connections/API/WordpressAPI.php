<?php
namespace Connections\API;

use Illuminate\Support\Facades\Config;
use GuzzleHttp\Client;

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

        $this->client = new Client([ 'base_uri' => $this->base_url ]);

        // if ($content) {
        //     $this->token = (new \oAuth\API\WordPressAuth)->getToken($content);
        // }
        // $this->domain = $content->connection->getSettings()->url;
    }

    public function createPost()
    {
        $content = $this->content;
        $connectionSettings = $this->connection->getSettings();

        // - standardize return
        $response = ['success' => false, 'response' => []];
        try {
            // - Tag Data
            $tags = [];
            if ($content->tags) {
                foreach ($content->tags as $tag) {
                    $tags[] = trim($tag->tag);
                }
            }
            // Compile data
            $postdata = [
                'tite' =>  $content->title,
                'content' => $content->body,
                'tags' => $tags,
            ];

            // - Create Options and Header Data
            $options = [
                'http' => [
                    'method'  => 'POST',
                    'ignore_errors' => true,
                    'header' => [
                        0 => 'authorization: Bearer '. $connectionSettings->access_token,
                        1 => 'Content-Type: application/x-www-form-urlencoded',
                    ],
                    'content' => http_build_query($postdata)
                ]
            ];
            // REST API url
            $url = $this->base_url.'sites/' . $connectionSettings->blog_id . '/posts/new';

            $context  = stream_context_create($options);
            $apiResponse = file_get_contents($url, false, $context);

            dd([ 'apiResponse' => $apiResponse ]);

            $response = [
                'success' => true,
                'response' => json_decode($apiResponse)
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
        $response = $this->client->get('sites/' . $blogUrl);

        return json_decode((string) $response->getBody());
    }
}