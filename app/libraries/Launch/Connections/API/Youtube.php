<?php namespace Launch\Connections\API;

use Illuminate\Support\Facades\Config;
use GuzzleHttp\Client;

// Eh... google api lib not namespaced
use Google_Client;
use Google_Service_Oauth2;
use Google_Service_YouTube;
use Google_Service_YouTube_VideoSnippet;
use Google_Service_YouTube_VideoStatus;
use Google_Service_YouTube_Video;
use Google_Http_MediaFileUpload;

/**
 * Configure google app: @see https://console.developers.google.com/project
 * @see https://github.com/google/google-api-php-client
 * Use master of this lib, as latest tag has error in media upload
 */
class YoutubeAPI extends GoogleAPI {
  
  protected $configKey = 'services.youtube';

  // Youtube service api
  protected $api = null;

  protected function getClient()
  {
    if ( ! $this->client) {
      // Setup google client
      $this->client = new Google_Client;
      $this->client->setClientId($this->config['key']);
      $this->client->setClientSecret($this->config['secret']);
      $this->client->setScopes('https://www.googleapis.com/auth/youtube');

      // Use refresh token
      try {
        $token = $this->getRefreshToken();
      } catch (\Exception $e) {
        return;
      }

      if ( ! $token) {
        // @todo: Handle this better
        throw new \Exception('Invalid token');
      }
      // Google lib expects token in this format
      $token = json_encode([
        'access_token' => $token,
        'created' => time(),
        'expires_in' => 3600
      ]);
      $this->client->setAccessToken($token);
      $this->api = new Google_Service_YouTube($this->client);
    }
    return $this->client;
  }

  public function getIdentifier()
  {
    $me = $this->getMe();
    return ucwords($me->user->displayName);
  }

  public function getMe()
  {
    // This gets google + plus profile info
    if ( ! $this->me) {
      $api = new Google_Service_Oauth2($this->getClient());
      $this->me = $api->userinfo->get();
    }
    return $this->me;
  }

  public function getUrl()
  {
    $me = $this->getMe();
    return $me->link;
  }

  protected function getRefreshToken()
  {
    $client = new Client;
    $response = $client->post('https://accounts.google.com/o/oauth2/token', [
      'body' => [
        'refresh_token' => $this->accountConnection['settings']['token']->getRefreshToken(),
        'client_id' => $this->config['key'],
        'client_secret' => $this->config['secret'],
        'grant_type' => 'refresh_token'
      ]
    ]);
    return $response->json()['access_token'];
  }

  /**
   * @see https://developers.google.com/youtube/v3/code_samples/php#resumable_uploads
   */
  public function postContent($content)
  {
    $response = ['success' => true, 'response' => []];
    try {

      $client = $this->getClient();

      $videoPath = $content->upload->getAbsPath();

      $snippet = new Google_Service_YouTube_VideoSnippet();
      $snippet->setTitle($content->title);
      $snippet->setDescription(strip_tags($content->body));
      if ($content->tags) {
        $tags = [];
        foreach ($content->tags as $tag) {
          $tags[] = trim($tag->tag);
        }
        $snippet->setTags($tags);
      }
      // Numeric video category
      // @see https://developers.google.com/youtube/v3/docs/videoCategories/list 
      // 22 = People & Blogs @todo: Should we allow the user to 
      // specify the category?
      $snippet->setCategoryId("22");
      $status = new Google_Service_YouTube_VideoStatus;
      $status->privacyStatus = 'public';

      // Associate the snippet and status objects with a new video resource
      $video = new Google_Service_YouTube_Video;
      $video->setSnippet($snippet);
      $video->setStatus($status);

      // Specify the size of each chunk of data, in bytes. Set a higher value for
      // reliable connection as fewer chunks lead to faster uploads. Set a lower
      // value for better recovery on less reliable connections.
      $chunkSizeBytes = 1 * 1024 * 1024;

      // Setting the defer flag to true tells the client to return a request which can be called
      // with ->execute(); instead of making the API call immediately.
      $client->setDefer(true);

      // Create a request for the API's videos.insert method to create and upload the video.
      $insertRequest = $this->api->videos->insert("status,snippet", $video);

      // Create a MediaFileUpload object for resumable uploads.
      $media = new Google_Http_MediaFileUpload(
        $client,
        $insertRequest,
        'video/*',
        null,
        true,
        $chunkSizeBytes
      );
      $media->setFileSize(filesize($videoPath));

      // Read the media file and upload it chunk by chunk.
      $status = false;
      $handle = fopen($videoPath, "rb");

      while (!$status && !feof($handle)) {
        $chunk = fread($handle, $chunkSizeBytes);
        $status = $media->nextChunk($chunk);
      }
      fclose($handle);

      // If you want to make other calls after the file upload, set setDefer back to false
      $client->setDefer(false);

      $response['success'] = true;
      $response['response'] = $status;
    } catch (\Exception $e) {
      $response['success'] = false;
      $response['response'] = $status;
      $response['error'] = $e->getMessage();
    }
    return $response;
  }

}