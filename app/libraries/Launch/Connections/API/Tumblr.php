<?php namespace Launch\Connections\API;

use Illuminate\Support\Facades\Config;
use GuzzleHttp\Client;

class TumblrAPI implements Connection {

  private $accountConnection;
  private $config = [];
  private $baseUrl = 'http://api.tumblr.com';
  private $client;

  public function __construct(array $accountConnection)
  {
    $this->accountConnection = $accountConnection;
    $this->config = Config::get('services.tumblr');
  }

  public function setupClient($method, $path, $params = [], $headers = [])
  {
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
    // Get the oauth signature to put in the request header
    $token = $this->accountConnection['settings']['token'];

    $consumer = new \Eher\OAuth\Consumer(
      // Consumer key
      $this->config['key'],
      // Consumer secret
      $this->config['secret']
    );
    $token = new \Eher\OAuth\Token(
      // Oauth token
      $token->getAccessToken(),
      // Oauth secret
      $token->getAccessTokenSecret()
    );
    $oauth = \Eher\OAuth\Request::from_consumer_and_token(
      $consumer,
      $token,
      $method,
      $this->baseUrl .'/'. $path,
      $params
    );
    $signatureMethod = new \Eher\OAuth\HmacSha1;
    $oauth->sign_request($signatureMethod, $consumer, $token);
    $authHeader = $oauth->to_header();
    $pieces = explode(' ', $authHeader, 2);
    $authString = $pieces[1];
    $request = $this->client->createRequest($method, $this->baseUrl .'/'. $path, [
      'headers' => [
        'Authorization' => $authString,
      ],
      'body' => $params
    ]);
    return $request;
  }

  public function getUserInfo()
  {
    $request = $this->setupClient('GET', 'v2/user/info');
    $response = $this->client->send($request);
    $info = $response->json();
    if ($info['meta']['msg'] == 'OK') {
      return $info['response'];
    }
  }

  /**
   * @see http://www.tumblr.com/docs/en/api/v2#posting
   */
  public function postContent($content)
  {
    // Get the name of the user's blog
    $userInfo = $this->getUserInfo();
    $name = $userInfo['user']['blogs'][0]['name'];

    // Setup post params
    $params = [
      'type' => 'text',
      'state' => 'published',
      'format' => 'html',
      'title' => $content->title,
      'body' => $content->body
    ];
    // Add comma sep tags
    if ($content->tags) {
      foreach ($content->tags as $tag) {
        $tags[] = trim($tag->tag);
      }
      if ( ! empty($tags)) {
        $params['tags'] = implode(',', $tags);
      }
    }

    $response = ['success' => true, 'response' => []];
    try {
      $request = $this->setupClient('POST', 'v2/blog/'. $name .'.tumblr.com/post', $params);
      $apiResponse = $this->client->send($request);
      $response['response'] = $apiResponse->json();
    } catch (\Exception $e) {
      $response['success'] = false;
      $request = $e->getRequest();
      $response['response'] = $request->getBody();
      $response['error'] = $e->getMessage();
    }
    return $response;
  }

  public function getFriends($page = 0, $perPage = 1000)
  {
    // Not needed ? 
  }

  public function sendDirectMessage(array $friends, array $message, $contentID, $contentType, $accountID)
  {
    // Not needed ?
  }

}