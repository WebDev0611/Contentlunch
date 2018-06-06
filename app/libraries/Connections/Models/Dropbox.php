<?php 
namespace Connections\Models;

use Spatie\Dropbox\Client;
use Spatie\FlysystemDropbox\DropboxAdapter;
use League\Flysystem\Filesystem;

class Dropbox {

    protected $dropbox;

    public function __construct($token)
    {
        $client = new Client($token);
        $adapter = new DropboxAdapter($client);

        $this->dropbox = new Filesystem($adapter);
    }


    public function uploadFile($filePath, $fileName, $mimeType = null)
    {
        // - Get Raw Data to send to uploader
        $data = fopen($filePath,'r');

        return $this->dropbox->put('/'.$fileName , $data);
    }
}