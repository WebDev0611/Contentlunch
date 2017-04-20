<?php

namespace Connections\API;

use App\MailChimp;
use Illuminate\Support\Facades\Config;

class MailchimpAPI {

    public function __construct ($content = null, $connection = null) {
        $this->client = null;
        $this->content = $content;
        $this->connection = $connection ? $connection : $this->content->connection;
        $this->datacenter = Config::get('services.mailchimp.datacenter');
    }

    public function createCampaign ()
    {
        $settings = json_decode($this->connection->settings);
        $MailChimp = new MailChimp($settings->access_token, 'oAuth2', null, $this->datacenter);

        $response = [
            'success' => false,
            'response' => []
        ];

        $campaignData = [
            'type'             => 'regular', // accepted values: regular, plaintext, absplit, rss, variate
            'recipients'       => [
                'list_id' => '0c0fcaa3fe'   // string, TODO: GET 'lists' and create dropdown
            ],
            'settings'         => [
                'subject_line' => $this->content->email_subject,
                'title'        => $this->content->title,
                'from_name'    => 'Content Launch',
                'reply_to'     => 'testing@contentlaunch.app'
            ],
            'variate_settings' => [
                'winner_criteria' => 'opens'    // Possible Values: opens clicks manual total_revenue
            ],
            'rss_opts'         => [
                'feed_url'  => 'http://contentlaunch.app',
                'frequency' => 'daily'  // Possible Values: daily weekly monthly
            ]
        ];

        $result = $MailChimp->post('campaigns', $campaignData);
        //$result = $MailChimp->get('lists');
        //$result = $MailChimp->get('campaigns');

        /*
        $result = $MailChimp->put('campaigns/9887044d82/content', [
            'html' => '<h1>Lorem Ipsum 1234</h1><p>Abcd Efg Hijk</p>'
        ]);
        */

        return $response;
    }
}