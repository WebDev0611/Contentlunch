<?php namespace Launch\Connections\API;

use Illuminate\Support\Facades\Config;
use GuzzleHttp\Client;

class VimeoAPI extends AbstractConnection {

  protected $configKey = 'services.vimeo';

  protected $base_url = 'https://api.vimeo.com';

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

  public function getMe()
  {
  	if ( ! $this->meData) {
  		$client = $this->getClient();
  		$response = $client->get('me');
  	}
  }

  public function getIdentifier()
  {
    return null;
  }

  public function postContent($content)
  {
  	return null;
  }

}