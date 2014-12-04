<?php namespace Launch\Connections\API;

use Illuminate\Support\Facades\Config;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

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

    /**
     * Get the external user / account id
     */
    public function getExternalId() {
        $me = $this->getMe();
        return $me['ID'] . $me['token_site_id'];
    }

    public function getSiteData() {
        try {
            $me = $this->getMe();
            $client = $this->getClient();
            $response = $client->get('rest/v1/sites/' . $me['token_site_id']);
        } catch (ClientException $e) {
            $responseBody = json_decode($e->getResponse()->getBody(true));
            $response['success'] = false;
            $response['error'] = $responseBody->message;
        }
        return $response;
    }

    /* 
    * Sends a request to the connection to
    * see if anything returns. Use this to
    * determine if the WordPress REST API is
    * functioning properly on the customer's
    * server. If it's not, return the error
    * message.
    */
    public function canNotConnect() {
        try {
            $this->getClient()->get('rest/v1/sites/' . $this->getMe()['token_site_id']);
        } catch (ClientException $e) {
            $responseBody = json_decode($e->getResponse()->getBody(true));
            return $responseBody->message;
        }
        return false;
    }

    public function getIdentifier() {
        $me = $this->getMe();
        $client = $this->getClient();
        $response = $client->get('rest/v1/sites/' . $me['token_site_id']);
        $blog = $response->json();

        return $blog['name'];
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
                    'tags' => $tags,
                    'status' => "draft"
                ]
            ]);
            $response['success'] = true;
            $response['response'] = $apiResponse->json();
            $response['external_id'] = $response['response']['ID'];
        } catch (ClientException $e) {
            $responseBody = json_decode($e->getResponse()->getBody(true));
            $response['success'] = false;
            $response['error'] = $responseBody->message;
        }

        return $response;
    }

    public function updateStats($accountConnectionId) {

        $temp = \AccountConnection::find($accountConnectionId)
            ->content()
            ->withPivot('external_id', 'likes', 'shares')
            ->get();

        $content = array();
        foreach($temp as $c) {
            $content[$c->pivot->external_id] = $c;
        }

        //var_dump($content);

        $ids = array_keys($content);

        $client = $this->getClient();

        $me = $this->getMe();

        //TODO paging

        $url = 'rest/v1/sites/' . $me['token_site_id'] . '/posts';
        $query = array();
        foreach($ids as $id) {
            $query[] = "filter[post__in][]=$id";
        }
        $query[] = 'number=100';
        $response = $client->get($url . '?' . implode('&', $query));
        $response = $response->json();
//        var_dump($response->json());die;

        if($response['found'] > 100) {
            echo 'warning multiple pages';
        }

        //var_dump($tweets);
        $posts = array();
        foreach($response['posts'] as $post) {
            $posts[$post['ID']] = $post;
        }

        $count = 0;
        foreach($posts as $post) {
            $id = $post['ID'];
            if(isset($content[$id])) {
                $count++;

                $content[$id]->pivot->likes = $post['like_count'];
                $content[$id]->pivot->comments = $post['comment_count'];
                $content[$id]->pivot->save();
            }
        }

        return json_encode(['success' => 1, 'count' => $count]);
    }

}