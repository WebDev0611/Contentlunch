<?php

namespace Connections\API;

use App\MailChimp;
use Illuminate\Support\Facades\Config;
use oAuth\API\MailchimpAuth;

class MailchimpAPI {

    public function __construct ($content = null, $connection = null) {
        $this->client = null;
        $this->content = $content;
        $this->connection = $connection ? $connection : $this->content->connection;
        $this->datacenter = Config::get('services.mailchimp.datacenter');
    }

    /**
     * We're creating a Campaign, but using the app-generic name 'createPost'
     */
    public function createPost ()
    {

    }
}