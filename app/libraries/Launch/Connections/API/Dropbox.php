<?php namespace Launch\Connections\API;

use Illuminate\Support\Facades\Config;
use Dropbox\Client;
use Dropbox\WriteMode;

class DropboxAPI extends AbstractConnection {

  protected $configKey = 'services.dropbox';

  protected function getClient()
  {
    if ( ! $this->client) {
      $token = $this->getAccessToken();
      $this->client = new Client($token, $this->config['key']);
    }
    return $this->client;
  }

  public function getIdentifier()
  {  
    $info = $this->getMe();
    return $info['display_name'];
  }

  public function getMe()
  {
    if ( ! $this->me) {
      $client = $this->getClient();
      $this->me = $client->getAccountInfo();
    }
    return $this->me;
  }

  public function getUrl()
  {
    return self::NA_TEXT;
  }

  public function postContent($content)
  {
    $response = ['success' => false, 'response' => []];
    $client = $this->getClient();
    if ( ! $client) {
      return $apiResponseonse;
    }
    try {
      $f = fopen($content->upload->getAbsPath(), 'rb');
      $apiResponse = $client->uploadFile('/'. $content->upload['filename'], WriteMode::add(), $f);
      fclose($f);
      if ( ! $apiResponse) {
        throw new \Exception("Couldn't upload file");
      }
      $response['success'] = true;
      $response['response'] = $apiResponse;
    }
    catch (\Exception $e) {
      $response['success'] = false;
      $response['response'] = $apiResponse;
      $response['error'] = $e->getMessage();
    }
    return $response;
  }

}