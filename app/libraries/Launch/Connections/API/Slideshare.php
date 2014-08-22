<?php namespace Launch\Connections\API;

use Launch\Exception\OAuthTokenException;
use Illuminate\Support\Facades\Config;
use GuzzleHttp\Client;

class SlideshareAPI extends AbstractConnection {

  protected $base_url = 'https://www.slideshare.net/api/2/get_slideshow';

  protected $configKey = 'services.slideshare';

  protected function getClient()
  {
    if ( ! $this->client) {
      $this->client = new Client([
        'base_url' => $this->base_url,
        'defaults' => [
          'config' => [
            'curl' => [
              CURLOPT_SSL_VERIFYPEER => false
            ]
          ],
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
    
  }

  public function getIdentifier()
  {
    return null;
  }

  // No api endpoint really returns user data
  // Get user tags to test connectivity status
  public function getMe()
  {
    $this->getUserTags();
    return null;
  }

  public function getUrl()
  {
    return null;
  }

  public function getUserCredentials()
  {
    if (empty($this->accountConnection['settings']['username']) ||
        empty($this->accountConnection['settings']['password'])) {
      throw new OAuthTokenException('Invalid connection, missing credentials');
    }
    return $this->accountConnection['settings'];
  }

  public function getUserTags()
  {
    $client = $this->getClient();
    $creds = $this->getUserCredentials();
    $response = $client->get('/api/2/get_user_tags?username='. $creds['username'] .'&password='. $creds['password']);
    echo 'here';
    $body = $response->getBody();
    echo $body;
    die;
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