<?php namespace Launch\Connections\API;

use Illuminate\Support\Facades\Config;
use GuzzleHttp\Client;

// Eh... google api lib not namespaced
use Google_Client;
use Google_Service_Oauth2;
use Google_Service_Drive;
use Google_Service_Drive_DriveFile;
use Google_Http_MediaFileUpload;

class GoogleDriveAPI extends AbstractConnection {

  protected $configKey = 'services.google_drive';

  protected $meData = null;

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

  protected function getClient()
  {
    if ( ! $this->client) {
      // Setup google client
      $this->client = new Google_Client;
      $this->client->setClientId($this->config['key']);
      $this->client->setClientSecret($this->config['secret']);
      $this->client->setScopes('https://www.googleapis.com/auth/drive');

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
    }
    return $this->client;
  }

  public function getMe()
  {
    if ( ! $this->meData) {
      $client = $this->getClient();
      $api = new Google_Service_Oauth2($client);
      $userInfo = $api->userinfo->get();
      $api = new Google_Service_Drive($client);
      $this->meData = $api->about->get();
    }
    return $this->meData;
  }

  public function getIdentifier()
  {
    $info = $this->getMe();
    if ($info) {
      return ucwords($info->user->displayName);
    }
  }

  public function getUrl()
  {
    // Not applicable
    return $this->notApplicableText;
  }

  /**
   * @see https://developers.google.com/blogger/docs/3.0/reference/posts#resource
   */
  public function postContent($content)
  {
    $response = ['success' => true, 'response' => []];
    try {
      $client = $this->getClient();

      $service = new Google_Service_Drive($client);

      $filePath = $content->upload->getAbsPath();

      $file = new Google_Service_Drive_DriveFile;
      $file->setDescription($content->body);
      $file->setTitle($content->title);

      $chunkSizeBytes = 1 * 1024 * 1024;

      // Call the API with the media upload, defer so it doesn't immediately return.
      $client->setDefer(true);
      $request = $service->files->insert($file);

      // Create a media file upload to represent our upload process.
      $media = new Google_Http_MediaFileUpload(
          $client,
          $request,
          $content->upload->mimetype,
          null,
          true,
          $chunkSizeBytes
      );
      $media->setFileSize(filesize($filePath));

      // Upload the various chunks. $status will be false until the process is
      // complete.
      $status = false;
      $handle = fopen($filePath, "rb");
      while (!$status && !feof($handle)) {
        $chunk = fread($handle, $chunkSizeBytes);
        $status = $media->nextChunk($chunk);
      }

      // The final value of $status will be the data from the API for the object
      // that has been uploaded.

      fclose($handle);
    
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