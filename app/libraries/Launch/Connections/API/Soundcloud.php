<?php namespace Launch\Connections\API;

use Illuminate\Support\Facades\Config;
use Soundcloud\Service as SoundcloudSDK;
use Soundcloud\Exception\InvalidHttpResponseCodeException;

class SoundcloudAPI implements Connection
{
  
  private $accountConnection;
  private $config = [];
  // Soundcloud sdk instance
  private $api;

  protected static $client = null;

  public function __construct(array $accountConnection) 
  {
    // Setup connection to the API (SDK ?)
    $this->accountConnection = $accountConnection;
    $this->config = Config::get('services.soundcloud');
    $this->api = new SoundcloudSDK($this->config['key'], $this->config['secret']);

    $this->api->setAccessToken($accountConnection['settings']['token']->getAccessToken());
/*
    if ( ! static::$client) {
      static::$client = new Client(array(
        'defaults' => array(
          'timeout' => 20,
          'connect_timeout' => 2,
          'allow_redirects' => array('max' => 2, 'strict' => false, 'referer' => true),
          'headers' => array(
            'User-Agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/31.0.1650.63 Chrome/31.0.1650.63 Safari/537.36'
          )
        )
      ));
    }*/
  }

  public function getFriends($page = 0, $perPage = 1000) 
  {
    // Not needed ? 
  }

  public function getMe()
  {
    $response = $this->api->get('me');
    print_r($response);
  }

  public function postContent($content)
  {
    $response = ['success' => true, 'response' => []];
    // Build the track
    $track = [
      'track[title]' => $content->title,
      'track[tags]' => 'tags separated by space',
      'track[asset_data]' => '@/absolute/path/to/track.mp3'
    ];
    $trackTags = [];
    if ($content->tags) {
      foreach ($content->tags as $tag) {
        $trackTags[] = trim($tag->tag);
      }
    }
    $track['track[tags]'] = implode(' ', $trackTags);
    // Get absolute path to the audio upload
    // Prepend "@" to tell cURL to upload the actual file
    $upload = '@/' . base_path() . $content->upload['path'] . $content->upload['filename'];
    $track['track[asset_data]'] = $upload;
    try {
      $apiResponse = $this->api->post('tracks', $track);
      $response['success'] = true;
      $response['response'] = $apiResponse;
    }
    catch (InvalidHttpResponseCodeException $e) {
      $response['success'] = false;
      $response['response'] = $e->getHttpBody();
      $response['error'] = $e->getMessage();
    }
    catch (\Exception $e) {
      $response['success'] = false;
      $response['response'] = $apiResponse;
      $response['error'] = $e->getMessage();
    }
    return $response;
  }

  public function sendDirectMessage(array $friends, array $message, $contentID, $contentType, $accountID) {
    // Not needed ?
  }

}