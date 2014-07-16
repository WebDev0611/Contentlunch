<?php namespace Launch\Connections\API;

use Illuminate\Support\Facades\Config;
use GuzzleHttp\Client;

class WordpressAPI implements Connection {

  private $accountConnection;
  private $config = [];
  
  protected $base_url = 'https://public-api.wordpress.com';
  protected $client = null;

  public function __construct(array $accountConnection)
  {
    $this->accountConnection = $accountConnection;
    $this->config = Config::get('services.wordpress');
    $access_key = $this->accountConnection['settings']['token']->getAccessToken();
    $this->client = new Client([
      'base_url' => $this->base_url,
      'defaults' => [
        'headers' => [
          'Authorization' => 'Bearer '. $access_key
        ]
      ]
    ]);
  }

  public function getMe()
  {
    $response = $this->client->get('rest/v1/me');
    return $response->json();
  }

  public function postContent($content)
  {
$me = $this->getMe();
    $options  = array (
  'http' => 
  array (
    'ignore_errors' => true,
    'method' => 'POST',
    'header' => 
    array (
      0 => 'authorization: Bearer '.$this->accountConnection['settings']['token']->getAccessToken() ,
      1 => 'Content-Type: application/x-www-form-urlencoded',
    ),
    'content' => http_build_query(   
      array (
        'title' => 'Hello World',
        'content' => 'Hello. I am a test post. I was created by the API',
        'tags' => 'tests',
        'categories' => 'API',
      )
    ),
  ),
);
 
$context  = stream_context_create( $options );
$response = file_get_contents(
  'https://public-api.wordpress.com/rest/v1/sites/'. $me['ID'] .'/posts/new/',
  false,
  $context
);
$response = json_decode( $response );
print_r($response);
die;


    $me = $this->getMe();
    $tags = [];
    if ($content->tags) {
      foreach ($content->tags as $tag) {
        $tags[] = trim($tag->tag);
      }
    }
    $apiResponse = $this->client->post('rest/v1/sites/'. $me['ID'] .'/posts/new', [
      'headers' => [
        'Content-Type' => 'application/x-www-form-urlencoded'
      ],
      'body' => [
        'title' => $content->title,
        'content' => $content->body,
        'tags' => 'test',
        'categories' => 'API'
      ]
    ]);
    print_r($apiResponse);
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