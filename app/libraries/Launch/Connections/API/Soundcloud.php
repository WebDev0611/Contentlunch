<?php namespace Launch\Connections\API;

use Illuminate\Support\Facades\Config;
use Soundcloud\Service as SoundcloudSDK;
use Soundcloud\Exception\InvalidHttpResponseCodeException;

class SoundcloudAPI extends AbstractConnection {
  
  protected $configKey = 'services.soundcloud';

  protected function getClient()
  {
    if ( ! $this->client) {
      $this->client = new SoundcloudSDK($this->config['key'], $this->config['secret']);
      $this->client->setAccessToken($this->getAccessToken());
    }
    return $this->client;
  }

  public function getIdentifier()
  {
    return null;
  }

  public function getMe()
  {
    $client = $this->getClient();
    $response = $client->get('me');
    return $response;
  }

  public function postContent($content)
  {
    $client = $this->getClient();
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
      $apiResponse = $client->post('tracks', $track);
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

}