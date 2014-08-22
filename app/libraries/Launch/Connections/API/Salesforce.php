<?php namespace Launch\Connections\API;

use Illuminate\Support\Facades\Config;
use GuzzleHttp\Client;

class SalesforceAPI extends AbstractConnection {

  protected $configKey = 'services.salesforce';

  protected function getClient()
  {
    return null;
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

  public function getMe()
  {
    return null;
  }

  public function getUrl()
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