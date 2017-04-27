<?php

namespace Connections\API;

use App\Http\Controllers\ExportController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class DropboxAPI {

    protected $base_url = 'https://api.dropboxapi.com/1';
    protected $put_url = 'https://content.dropboxapi.com/1';
    protected $dropbox;

    public function __construct ($content = null, $connection = null)
    {
        $this->client = null;
        $this->content = $content;
        $this->connection = $connection ? $connection : $this->content->connection;

        $settings = json_decode($this->connection->settings);

        $this->dropbox = App::make('\Connections\Models\Dropbox');
        $this->dropbox->setConfig([
            'token' => $settings->access_token,
            'app'   => 'Content Launch App',
        ]);
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
