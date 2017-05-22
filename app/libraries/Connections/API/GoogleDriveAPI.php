<?php

namespace Connections\API;

use App\Http\Controllers\ExportController;
use Google_Service_Drive;
use Google_Service_Drive_DriveFile;
use oAuth\API\GoogleDriveAuth;

class GoogleDriveAPI {

    protected $auth;

    public function __construct ($content = null, $connection = null)
    {
        $this->client = null;
        $this->content = $content;
        $this->connection = $connection ? $connection : $this->content->connection;
        $this->auth = new GoogleDriveAuth();

        $this->refreshConnection();
    }

    public function createPost ()
    {
        // Call export method
        $export = new ExportController();
        $exportResponse = $export->content($this->content->id, 'docx', $this);

        if ($exportResponse->getStatusCode() == 200) {
            $response = [
                'success'  => true,
                'response' => 'File published',
            ];

            $this->content->setPublished();
        }
        else {
            $response = [
                'success' => false,
                'error'   => $exportResponse->getStatusCode()
            ];
        }

        return $response;
    }

    public function uploadFile ($filePath, $fileName, $fileMimetype)
    {
        $dr_service = new Google_Service_Drive($this->client);
        $file = new Google_Service_Drive_DriveFile();

        $uploadType = 'media';
        $filedescription = 'Published with Content Launch at ' . date('Y-m-d H:i:s');

        $file->setName($fileName);
        $file->setDescription($filedescription);
        $file->setMimeType($fileMimetype);

        $data = file_get_contents($filePath);

        $createdFile = $dr_service->files->create($file, [
            'data'       => $data,
            'mimeType'   => $fileMimetype,
            'uploadType' => $uploadType
        ]);
    }

    private function refreshConnection ()
    {
        $settings = json_decode($this->connection->settings);

        if (time() > ($settings->created + $settings->expires_in)) {
            $refreshedSettings = $this->auth->refreshToken($settings->refresh_token);
            $this->connection->settings = json_encode($refreshedSettings);
            $this->connection->save();
        } else {
            $this->auth->client->setAccessToken($settings->access_token);
        }

        $this->client = $this->auth->client;
    }
}