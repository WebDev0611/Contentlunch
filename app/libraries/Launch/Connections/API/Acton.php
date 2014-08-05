<?php namespace Launch\Connections\API;

use Illuminate\Support\Facades\Config;
use GuzzleHttp\Client;

/**
 * @see http://developer.act-on.com/documentation/oauth/
 */
class ActonAPI extends AbstractConnection {

  protected $configKey = 'services.acton';

  protected $base_url = 'https://restapi.actonsoftware.com';

  protected $meData = null;

  protected function getClient()
  {
    if ( ! $this->client) {
      $token = $this->getAccessToken();
      $this->client = new Client([
        'base_url' => $this->base_url,
        'defaults' => [
          'headers' => [
            'Authorization' => 'Bearer '. $token
          ]
        ]
      ]);
    }
    return $this->client;
  }

  protected function getRefreshToken()
  {
    $client = $this->getClient();
    $response = $client->post('/token', [
      'body' => [
        'refresh_token' => $this->accountConnection['settings']['token']->getRefreshToken(),
        'client_id' => $this->config['key'],
        'client_secret' => $this->config['secret'],
        'grant_type' => 'refresh_token'
      ]
    ]);
    return $response->json()['access_token'];
  }

  public function getMe()
  {
    if ( ! $this->meData) {
      $client = $this->getClient();
      $response = $client->get('/api/1/account');
      $this->meData = $response->json();
    }
    return $this->meData;
  }

  public function getIdentifier()
  {
    $data = $this->getMe();
    if ($data) {
      return $data['cname'];
    }
  }

  public function getUrl()
  {
    return $this->notApplicableText;
  }

  /**
   */
  public function postEmailDraft($content)
  {
    $client = $this->getClient();
    $response = ['success' => true, 'response' => []];
    try {
      $apiResponse = $client->post('/api/1/message', [
        'body' => [
          'type' => 'draft',
          'subject' => $content->title,
          'htmlbody' => $content->body,
        ]
      ]);
      $response['success'] = true;
      $response['response'] = $apiResponse->json();
    } catch (\Exception $e) {
      $response['success'] = false;
      $response['error'] = $e->getMessage();
    }
    return $response;
  }

  public function postContent($content)
  {
    // The frontend is setup to allow posting to act-on
    // if the base_type is long_html, email, or blog_post
    // 
    $key = $content->content_type()->first()->key;
    switch ($key) {
      case 'blog-post':
        return $this->postBlog($content);
      break;
      case 'email':
      case 'workflow-email':
        return $this->postEmailDraft($content);
      break;
      case 'landing-page':
        return $this->postLandingPage($content);
      break;
      case 'website-page':
        return $this->postSitePage($content);
      break;
    }
  }

}