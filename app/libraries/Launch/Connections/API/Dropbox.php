<?php namespace Launch\Connections\API;

use Illuminate\Support\Facades\Config;
use Dropbox\Client;
use Dropbox\WriteMode;

/**
 * @see https://www.dropbox.com/developers/core/start/php
 */
class DropboxAPI implements Connection {

  private $accountConnection;
  private $config = [];

  protected $api;

  public function __construct(array $accountConnection) 
  {
    // Setup connection to the API (SDK ?)
    $this->accountConnection = $accountConnection;
    $this->config = Config::get('services.dropbox');
    $this->api = new Client($accountConnection['settings']['token']->getAccessToken(), $this->config['key']);
  }

  public function getAccountInfo()
  {
    $response = $this->api->getAccountInfo();
    print_r($response);
  }

  public function postContent($content)
  {
    $response = ['success' => true, 'response' => []];
    try {
      $f = fopen($content->upload->getAbsPath(), 'rb');
      $apiResponse = $this->api->uploadFile('/'. $content->upload['filename'], WriteMode::add(), $f);
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

  public function getFriends($page = 0, $perPage = 1000) 
  {
    // Not needed ? 
  }

  public function sendDirectMessage(array $friends, array $message, $contentID, $contentType, $accountID) 
  {
    // Not needed ?
  }

}