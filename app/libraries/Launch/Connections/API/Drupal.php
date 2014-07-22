<?php namespace Launch\Connections\API;

use Illuminate\Support\Facades\Config;
use GuzzleHttp\Client;

class DrupalAPI extends AbstractConnection {

  protected $configKey = 'services.drupal';

  protected function getClient()
  {
    return null;
  }

  public function getIdentifier()
  {
    return null;
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