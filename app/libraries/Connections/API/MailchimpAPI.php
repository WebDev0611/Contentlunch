<?php

namespace Connections\API;


use DrewM\MailChimp\MailChimp;

class MailchimpAPI {

    public function createCampaign ()
    {
        $MailChimp = new MailChimp('3f750b946b6d8dcece0fad5e3f94794f-us15');

        $campaignData = [
            'type'             => 'regular', // accepted values: regular, plaintext, absplit, rss, variate
            'recipients'       => [
                'list_id' => '0c0fcaa3fe'   // string, TODO: GET 'lists' and create dropdown
            ],
            'settings'         => [
                'subject_line' => 'This is a subject 3',
                'title'        => 'This is a title 3',
                'from_name'    => 'Content Launch',
                'reply_to'     => 'testing@contentlaunch.app'
            ],
            'variate_settings' => [
                'winner_criteria' => 'opens'    // opens clicks manual total_revenue
            ],
            'rss_opts'         => [
                'feed_url'  => 'http://contentlaunch.app',
                'frequency' => 'daily'  // Possible Values: daily weekly monthly
            ]
        ];

        //$result = $MailChimp->post('campaigns', $campaignData);
        //$result = $MailChimp->get('lists');
        //$result = $MailChimp->get('campaigns');

        $result = $MailChimp->put('campaigns/9887044d82/content', [
            'html' => '<h1>Lorem Ipsum 1234</h1><p>Abcd Efg Hijk</p>',
            //        'template' => [
            //            'id' => 12     // int, TODO: GET 'templates' and create dropdown
            //        ]
        ]);

        var_dump($result);
    }
}