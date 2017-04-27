<?php

namespace Connections\API;
use Illuminate\Support\Facades\App;

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

        $this->dropbox =  App::make('\Connections\Models\Dropbox');
        $this->dropbox->setConfig( [
            'token'  => $settings->access_token,
            'app'    => 'Content Launch App',
        ]);
    }

    public function uploadFile ($filePath, $fileName)
    {
        return $this->dropbox->uploadFile($filePath, $fileName);
    }
}
