<?php namespace Launch\Connections\API;

use Illuminate\Support\Facades\Config;
use GuzzleHttp\Client;

class VimeoAPI extends AbstractConnection {

  protected $configKey = 'services.vimeo';

  protected $base_url = 'https://api.vimeo.com';

  protected function getClient()
  {
    if ( ! $this->client) {
      $token = $this->getAccessToken();
      $this->client = new Client([
        'base_url' => $this->base_url,
        'defaults' => [
          'headers' => [
            'Authorization' => 'Bearer '. $token,
            'Accept' => 'application/vnd.vimeo.*+json;version=3.2'
          ]
        ]
      ]);
    }
    return $this->client;
  }

  public function getIdentifier()
  {
    $me = $this->getMe();
    return $me['name'];
  }

  public function getMe()
  {
    if ( ! $this->me) {
      $client = $this->getClient();
      $response = $client->get('me');
      $this->me = $response->json();
    }
    return $this->me;
  }

  public function getUrl()
  {
    $me = $this->getMe();
    return $me['link'];
  }

  public function postContent($content)
  {
  	return null;
  }

}