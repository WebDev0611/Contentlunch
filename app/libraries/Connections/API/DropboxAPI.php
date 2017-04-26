<?php

namespace Connections\API;

class DropboxAPI {

    public function __construct ($content = null, $connection = null)
    {
        $this->client = null;
        $this->content = $content;
        $this->connection = $connection ? $connection : $this->content->connection;
    }

    /*
     * Alias method for createDocument()
     */
    public function createPost ()
    {
        return $this->createDocument();
    }

    public function createDocument ()
    {
        return true;
    }
}