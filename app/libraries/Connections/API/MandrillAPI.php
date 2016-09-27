<?php

namespace Connections\API;

use Mandrill;

class MandrillAPI
{
    protected $mandrill;

    public function __construct()
    {
        $this->mandrill = new Mandrill(env('MANDRILL_API_KEY'));
    }

    public function send()
    {
        $message = [
            'html' => '<p>Test</p>',
            'to' => [
                [ 'email' => 'hard_bounce@test.mandrillapp.com' ],
            ],
            'from_email' => 'message.from_email@example.com',
            'from_name' => 'Example Name',
        ];

        $async = false;
        $ip_pool = 'Main Pool';
        $send_at = 'example send_at';
        $result = $this->mandrill->messages->send($message, $async, $ip_pool);

        return $result;

        // 'html' => '<p>Example HTML content</p>',
        //         'text' => 'Example text content',
        //         'subject' => 'example subject',
        //         'from_email' => 'message.from_email@example.com',
        //         'from_name' => 'Example Name',
        //         'to' => array(
        //             array(
        //                 'email' => 'recipient.email@example.com',
        //                 'name' => 'Recipient Name',
        //                 'type' => 'to'
        //             )
        //         ),
        //         'headers' => array('Reply-To' => 'message.reply@example.com'),
        //         'important' => false,
        //         'track_opens' => null,
        //         'track_clicks' => null,
        //         'auto_text' => null,
        //         'auto_html' => null,
        //         'inline_css' => null,
        //         'url_strip_qs' => null,
        //         'preserve_recipients' => null,
        //         'view_content_link' => null,
        //         'bcc_address' => 'message.bcc_address@example.com',
        //         'tracking_domain' => null,
        //         'signing_domain' => null,
        //         'return_path_domain' => null,
        //         'merge' => true,
        //         'merge_language' => 'mailchimp',
        //         'global_merge_vars' => array(
        //             array(
        //                 'name' => 'merge1',
        //                 'content' => 'merge1 content'
        //             )
        //         ),
        //         'merge_vars' => array(
        //             array(
        //                 'rcpt' => 'recipient.email@example.com',
        //                 'vars' => array(
        //                     array(
        //                         'name' => 'merge2',
        //                         'content' => 'merge2 content'
        //                     )
        //                 )
        //             )
        //         ),
        //         'tags' => array('password-resets'),
        //         'subaccount' => 'customer-123',
        //         'google_analytics_domains' => array('example.com'),
        //         'google_analytics_campaign' => 'message.from_email@example.com',
        //         'metadata' => array('website' => 'www.example.com'),
        //         'recipient_metadata' => array(
        //             array(
        //                 'rcpt' => 'recipient.email@example.com',
        //                 'values' => array('user_id' => 123456)
        //             )
        //         ),
        //     ];
    }
}