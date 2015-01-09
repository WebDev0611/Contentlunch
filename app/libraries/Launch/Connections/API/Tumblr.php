<?php namespace Launch\Connections\API;

use Illuminate\Support\Facades\Config;
use GuzzleHttp\Client;

class TumblrAPI extends AbstractConnection
{

    protected $baseUrl = 'http://api.tumblr.com';

    protected $configKey = 'services.tumblr';

    protected function getClient() {
        if (!$this->client) {
            $this->client = new Client([
                'base_url' => $this->baseUrl,
                'defaults' => [
                    'timeout' => 20,
                    'connect_timeout' => 2,
                    'allow_redirects' => ['max' => 3, 'strict' => false, 'referer' => true],
                    'headers' => [
                        'User-Agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/31.0.1650.63 Chrome/31.0.1650.63 Safari/537.36',
                        'Expect' => ''
                    ]
                ]
            ]);
        }

        return $this->client;
    }

    /**
     * Get the external user / account id
     */
    public function getExternalId()
    {
        $me = $this->getMe();
        return $me['user']['name'];
    }

    public function getIdentifier() {
        $me = $this->getMe();

        return $me['user']['blogs'][0]['name'];
    }

    public function getMe() {
        if (!$this->me) {
            $request = $this->getRequest('GET', 'v2/user/info');
            $client = $this->getClient();
            $response = $client->send($request);
            $info = $response->json();
            if ($info['meta']['msg'] == 'OK') {
                $this->me = $info['response'];
            }
        }

        return $this->me;
    }

    public function getRequest($method, $path, $params = [], $headers = []) {
        $client = $this->getClient();
        $consumer = new \Eher\OAuth\Consumer(
        // Consumer key
            $this->config['key'],
            // Consumer secret
            $this->config['secret']
        );
        $token = new \Eher\OAuth\Token(
        // Oauth token
            $this->getAccessToken(),
            // Oauth secret
            $this->getAccessTokenSecret()
        );
        $oauth = \Eher\OAuth\Request::from_consumer_and_token(
            $consumer,
            $token,
            $method,
            $this->baseUrl . '/' . $path,
            $params
        );
        $signatureMethod = new \Eher\OAuth\HmacSha1;
        $oauth->sign_request($signatureMethod, $consumer, $token);
        $authHeader = $oauth->to_header();
        $pieces = explode(' ', $authHeader, 2);
        $authString = $pieces[1];
        $request = $client->createRequest($method, $this->baseUrl . '/' . $path, [
            'headers' => [
                'Authorization' => $authString,
            ],
            'body' => $params
        ]);

        return $request;
    }

    public function getUrl() {
        $me = $this->getMe();

        return $me['user']['blogs'][0]['url'];
    }

    /**
     * @see http://www.tumblr.com/docs/en/api/v2#posting
     */
    public function postContent($content) {
        // Get the name of the user's blog
        $me = $this->getMe();
        $name = $me['user']['blogs'][0]['name'];

        // Setup post params
        $params = [
            'type' => 'text',
            'state' => 'draft',
            'format' => 'html',
            'title' => $content->title,
            'body' => $content->body
        ];
        // Add comma sep tags
        if ($content->tags) {
            foreach ($content->tags as $tag) {
                $tags[] = trim($tag->tag);
            }
            if (!empty($tags)) {
                $params['tags'] = implode(',', $tags);
            }
        }

        $response = ['success' => true, 'response' => []];
        try {
            $request = $this->getRequest('POST', 'v2/blog/' . $name . '.tumblr.com/post', $params);
            $client = $this->getClient();
            $apiResponse = $client->send($request);
            $response['response'] = $apiResponse->json();
        } catch (\Exception $e) {
            $response['success'] = false;
            $request = $e->getRequest();
            $response['response'] = $request->getBody();
            $response['error'] = $e->getMessage();
        }
        var_dump($response);

        return $response;
    }

    public function updateStats($accountConnectionId) {

        $content = \AccountConnection::find($accountConnectionId)
            ->content()
            ->withPivot('external_id', 'likes', 'shares')
            ->get();

        $me = $this->getMe();
        $name = $me['user']['blogs'][0]['name'];

        //need to do request manually because it doesn't use oauth for info retrieval
        $params = array(
            'api_key' => $this->config['key'],
            'notes_info' => 'true',
        );
        $url = 'http://api.tumblr.com/v2/blog/'.$name.'.tumblr.com/posts?';

        $count = 0;
        foreach ($content as $post) {
            $pivot = $post->pivot;

            $params['id'] = $pivot->external_id;

            $content = file_get_contents($url . http_build_query($params));
            $content = json_decode($content, true);

            if($content['meta']['status'] == 200) {
                if(!isset($content['response']['posts'][0]['notes'])) {
                    continue;
                }
                $notes = $content['response']['posts'][0]['notes'];

                $likes = 0;
                $shares = 0;
                $comments = 0;
                foreach($notes as $note) {
                    if($note['type'] == 'like') {
                        $likes++;
                    }
                    else if($note['type'] == 'reblog') {
                        $shares++;
                    }
                    else if($note['type'] == 'reply') {
                        $comments++;
                    }
                }


                $pivot->likes = $likes;
                $pivot->shares = $shares;
                $pivot->save();
            }
        }

        return json_encode(['success' => 1, 'count' => $count]);
    }

}