<?php namespace Launch\Connections\API;

use Illuminate\Support\Facades\Config;
use GuzzleHttp\Client;

// Eh... google api lib not namespaced
use Google_Client;
use Google_Service_Oauth2;
use Google_Service_Blogger;

class BloggerAPI extends AbstractConnection {

  protected $configKey = 'services.blogger';

  protected $meData = null;

  protected function getRefreshToken()
  {
    $client = new Client;
    $response = $client->post('https://accounts.google.com/o/oauth2/token', [
      'body' => [
        'refresh_token' => $this->accountConnection['settings']['token']->getRefreshToken(),
        'client_id' => $this->config['key'],
        'client_secret' => $this->config['secret'],
        'grant_type' => 'refresh_token'
      ]
    ]);
    return $response->json()['access_token'];
  }

  protected function getClient()
  {
    if ( ! $this->client) {
      // Setup google client
      $this->client = new Google_Client;
      $this->client->setClientId($this->config['key']);
      $this->client->setClientSecret($this->config['secret']);
      $this->client->setScopes('https://www.googleapis.com/auth/blogger');

      // Use refresh token
      try {
        $token = $this->getRefreshToken();
      } catch (\Exception $e) {
        return;
      }

      if ( ! $token) {
        // @todo: Handle this better
        throw new \Exception('Invalid token');
      }
      // Google lib expects token in this format
      $token = json_encode([
        'access_token' => $token,
        'created' => time(),
        'expires_in' => 3600
      ]);
      $this->client->setAccessToken($token);
    }
    return $this->client;
  }

  public function getMe()
  {
    if ( ! $this->meData) {
      $client = $this->getClient();
      $api = new Google_Service_Oauth2($client);
      $userInfo = $api->userinfo->get();
      $api = new Google_Service_Blogger($client);
      $blogs = $api->blogs->listByUser('self');
      $this->meData = [
        'user' => $userInfo,
        'blogs' => $blogs
      ];
    }
    return $this->meData;
  }

  public function getIdentifier()
  {
    $info = $this->getMe();
    if ($info) {
      $me = ucwords($info['user']->name);
      // Todo, user should be able to specify which blog to post to?
      if ( ! empty($info['blogs']->items[0]['name'])) {
        $me .= ' ('. $info['blogs']->items[0]['name'] .')';
      }
      return $me;
    }
  }

  public function getUrl()
  {
    $info = $this->getMe();
    if ($info) {
      if ( ! empty($info['blogs']->items[0]['url'])) {
        return $info['blogs']->items[0]['url'];
      }
    }
  }

  /**
   * @see http://www.tumblr.com/docs/en/api/v2#posting
   */
  public function postContent($content)
  {
    $response = ['success' => true, 'response' => []];
   
    return $response;
  }

}