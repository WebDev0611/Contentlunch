<?php namespace Launch\Connections\API;

use Illuminate\Support\Facades\Config;
use GuzzleHttp\Client;

// Eh... google api lib not namespaced
use Google_Client;
use Google_Service_Oauth2;
use Google_Service_Drive;
use Google_Service_Drive_DriveFile;
use Google_Http_MediaFileUpload;

class GoogleDriveAPI extends GoogleAPI {

  protected $configKey = 'services.google_drive';

  public function getIdentifier()
  {
    $me = $this->getMe();
    return ucwords($me->user->displayName);
  }

  public function getMe()
  {
    if ( ! $this->me) {
      $client = $this->getClient();
      $api = new Google_Service_Oauth2($client);
      $userInfo = $api->userinfo->get();
      $api = new Google_Service_Drive($client);
      $this->me = $api->about->get();
    }
    return $this->me;
  }

  public function getUrl()
  {
    // Not applicable
    return self::NA_TEXT;
  }

  /**
   * @see https://developers.google.com/blogger/docs/3.0/reference/posts#resource
   */
  public function postContent($content)
  {
    $contentType = $content->content_type()->first();

    if ($contentType->key == 'ebook' && $content->body != null) {
      return $this->postEbookContent($content);
    } else {

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

  public function postEbookContent($content)
  {
    $response = ['success' => true, 'response' => []];
    try {
      $client = $this->getClient();

      $service = new Google_Service_Drive($client);

      $filePath = base_path() . "/public/tmp_content_ebook_{$content->id}.pdf";

      $pdf = \App::make('dompdf');
      $pdf->loadHTML($content->body);

      $myFile = fopen($filePath, "w");
      fwrite($myFile, $pdf->stream());
      fclose($myFile);

      $file = new Google_Service_Drive_DriveFile;
      $file->setDescription($content->title);
      $file->setTitle($content->title);

      $chunkSizeBytes = 1 * 1024 * 1024;

      // Call the API with the media upload, defer so it doesn't immediately return.
      $client->setDefer(true);
      $request = $service->files->insert($file);

      // Create a media file upload to represent our upload process.
      $media = new Google_Http_MediaFileUpload(
          $client,
          $request,
          'application/pdf',
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