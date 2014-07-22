<?php namespace Launch\Connections\API;

use Illuminate\Support\Facades\Config;
use Dropbox\Client;
use Dropbox\WriteMode;

/**
 * @see https://www.dropbox.com/developers/core/start/php
 */
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

  public function getAccountInfo()
  {
    try {
      $client = $this->getClient();
    } catch (\Exception $e) {
      return '';
    }
    if ($client) {
      $response = $client->getAccountInfo();
      return $response;
    }
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

  public function getIdentifier()
  {  
    $info = $this->getAccountInfo();
    if ($info) {
      return $info['display_name'] .' - '. $info['email'];
    }
  }

}