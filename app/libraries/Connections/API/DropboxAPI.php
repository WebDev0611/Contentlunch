<?php

namespace Connections\API;

use App\Http\Controllers\ExportController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Connections\Models\Dropbox;


class DropboxAPI {

    protected $dropbox;

    public function __construct ($content = null, $connection = null)
    {
        $this->client = null;
        $this->content = $content;
        $this->connection = $connection ? $connection : $this->content->connection;

        $settings = json_decode($this->connection->settings);

        $this->dropbox = new Dropbox($settings->access_token);
    }

    public function createPost ()
    {
        $export = new ExportController();
        $exportResponse = $export->content($this->content->id, 'docx', $this->dropbox);

        if($exportResponse->getStatusCode() == 200) {
            $response = [
                'success'  => true,
                'response' => 'File published',
            ];

            $this->content->setPublished();
        } else {
            $response = [
                'success'  => false,
                'error' => $exportResponse->getStatusCode()
            ];
        }

        return $response;
    }
}
