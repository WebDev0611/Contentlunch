<?php namespace Launch\Connections\API;

use Illuminate\Support\Facades\Config;
use GuzzleHttp\Client;

/**
 * @see http://developer.wordpress.com/docs/api/
 */
class WordpressAPI extends AbstractConnection
{

    protected $configKey = 'services.wordpress';

    protected $base_url = 'https://public-api.wordpress.com';

    protected function getClient() {
        if (!$this->client) {
            $token = $this->getAccessToken();
            $this->client = new Client([
                'base_url' => $this->base_url,
                'defaults' => [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $token
                    ]
                ]
            ]);
        }

        return $this->client;
    }

    public function getIdentifier() {
        $me = $this->getMe();

        return $me['display_name'];
    }

    public function getMe() {
        if (!$this->me) {
            $client = $this->getClient();
            $response = $client->get('rest/v1/me');
            $this->me = $response->json();
        }

        return $this->me;
    }

    public function getUrl() {
        $me = $this->getMe();
        $client = $this->getClient();
        $response = $client->get('rest/v1/sites/' . $me['primary_blog']);
        $blog = $response->json();

        return $blog['URL'];
    }


    /**
     * @see https://developer.wordpress.com/docs/api/1/post/sites/%24site/posts/new/
     */
    public function postContent($content) {
        $client = $this->getClient();
        $response = ['success' => false, 'response' => []];
        try {
            $me = $this->getMe();
            $tags = [];
            if ($content->tags) {
                foreach ($content->tags as $tag) {
                    $tags[] = trim($tag->tag);
                }
            }
            $apiResponse = $client->post('rest/v1/sites/' . $me['token_site_id'] . '/posts/new', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->getAccessToken(),
                    'Content-Type' => 'application/x-www-form-urlencoded'
                ],
                'body' => [
                    'title' => $content->title,
                    'content' => $content->body,
                    'tags' => $tags
                ]
            ]);
            $response['success'] = true;
            $response['response'] = $apiResponse->json();
            $response['external_id'] = $response['response']['ID'];
            var_dump($response);
        } catch (\Exception $e) {
            $response['success'] = false;
            $response['response'] = $apiResponse->json();
            $response['error'] = $e->getMessage();
        }

        return $response;
    }

}