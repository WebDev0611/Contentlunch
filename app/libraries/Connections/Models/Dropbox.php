<?php

namespace Connections\Models;
use Dropbox\WriteMode;

class Dropbox {

    protected $dropbox;

    public function __construct(DropboxManager $dropbox)
    {
        $this->dropbox = $dropbox;
    }

    public function setConfig (array $config)
    {
        $this->dropbox->configuration = $config;
    }

    public function uploadFile($filePath, $fileName)
    {
        $stream = fopen($filePath,'r');

        return  $this->dropbox->uploadFile('/' . $fileName, WriteMode::add(), $stream, filesize($filePath));
    }
}