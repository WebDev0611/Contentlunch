<?php namespace Launch\Connections\API;

use Illuminate\Support\Facades\Config;
use GuzzleHttp\Client;

/**
 * @see http://developer.wordpress.com/docs/api/
  */
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

  /**
   * @see https://developer.wordpress.com/docs/api/1/post/sites/%24site/posts/new/
   */
  public function postContent($content)
  {
    $response = ['success' => false, 'response' => []];
    try {
      $me = $this->getMe();
      $tags = [];
      if ($content->tags) {
        foreach ($content->tags as $tag) {
          $tags[] = trim($tag->tag);
        }
      }
      $apiResponse = $this->client->post('rest/v1/sites/'. $me['token_site_id'] .'/posts/new', [
        'headers' => [
          'Authorization' => 'Bearer '.$this->accountConnection['settings']['token']->getAccessToken(),
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
    } catch (\Exception $e) {
      $response['success'] = false;
      $response['response'] = $apiResonse->json();
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