<?php

namespace Connections\API;

use App\MailChimp;
use oAuth\API\MailchimpAuth;

class MailchimpAPI {

    public function __construct ($content = null, $connection = null) {
        $this->client = null;
        $this->content = $content;
        $this->connection = $connection ? $connection : $this->content->connection;
    }
    
    public function createCampaign ()
    {

    }
}