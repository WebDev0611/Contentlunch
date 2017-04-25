<?php

namespace Connections\API;


use Artesaos\LinkedIn\Facades\LinkedIn;

class LinkedInAPI {

    protected $client;
    protected $content;
    protected $connection;

    public function __construct($content, $connection = null)
    {
        $this->content = $content;
        $this->connection = $connection ? $connection : $this->content->connection;
        $this->client = null;

        LinkedIn::setAccessToken(json_decode($connection->settings)->token);
    }
}