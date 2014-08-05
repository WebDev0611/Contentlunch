<?php namespace Launch\Connections\API;

use Illuminate\Support\Facades\Config;
use GuzzleHttp\Client;

// Eh... google api lib not namespaced
use Google_Client;
use Google_Service_Oauth2;
use Google_Service_Plus;
use Google_Service_Plus_Moment as Google_Moment;
use Google_Service_Plus_ItemScope as Google_ItemScope;

class GooglePlusAPI extends AbstractConnection {

  protected $configKey = 'services.google_plus';

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
      $this->client->setScopes(['https://www.googleapis.com/auth/plus.login']);

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
    // This gets google + plus profile info
    if ( ! $this->meData) {
      $api = new Google_Service_Oauth2($this->getClient());
      $this->meData = $api->userinfo->get();
    }
    return $this->meData;
  }

  public function getIdentifier()
  {
    $info = $this->getMe();
    if ($info) {
      return ucwords($info->name);
    }
  }

  public function getUrl()
  {
    $info = $this->getMe();
    return $info->link;
  }

  /**
   * @see https://developers.google.com/+/api/latest/moments/insert
   */
  public function postContent($content)
  {
    $response = ['success' => true, 'response' => []];
    try {
      $client = $this->getClient();

      // set $requestVisibleActions to write moments
      $requestVisibleActions = [
        'http://schemas.google.com/AddActivity',
        'http://schemas.google.com/ReviewActivity'];
      $client->setRequestVisibleActions($requestVisibleActions);

      $service = new Google_Service_Plus($client, ['debug' => true]);

      $moment_body = new Google_Moment();
      $moment_body->setType("http://schemas.google.com/AddActivity");
      //$moment_body->setType("http://schema.org/AddAction");
      $item_scope = new Google_ItemScope();
      $item_scope->setId("target-id-1");
      //$item_scope->setType("http://schema.org/AddAction");
      $item_scope->setType("http://schemas.google.com/AddActivity");
      $item_scope->setName($content->title);
      $item_scope->setDescription(strip_tags($content->body));
      $upload = $content->upload()->first();
      if ($upload && $upload->media_type == 'image') {
        $item_scope->setImage($upload->getUrl());
      }
      $moment_body->setTarget($item_scope);
      $momentResult = $service->moments->insert('me', 'vault', $moment_body);
    
      $response['success'] = true;
      $response['response'] = $momentResult;
    } catch (\Exception $e) {
      $response['success'] = false;
//      $response['response'] = $momentResult;
      $response['error'] = $e->getMessage();
    }
    return $response;
  }

}