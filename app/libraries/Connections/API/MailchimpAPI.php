<?php

namespace Connections\API;

use App\MailChimp;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Config;

class MailchimpAPI {

    public function __construct ($content = null, $connection = null)
    {
        $this->client = null;
        $this->content = $content;
        $this->connection = $connection ? $connection : $this->content->connection;
        $this->datacenter = Config::get('services.mailchimp.datacenter');
    }

    /*
     * Alias method for createCampaign()
     */
    public function createEmail ()
    {
        return $this->createCampaign();
    }

    public function createCampaign ()
    {
        $mailChimp = $this->createMailchimpInstance();

        $response = [
            'success'  => false,
            'response' => []
        ];

        try {
            $createResponse = $mailChimp->post('campaigns', $this->prepareCampaignData());

            /*
            if ($createResponse->getStatusCode() == '200') {

                $response = [
                    'success'  => true,
                    'response' => json_encode($createResponse),
                ];

                $this->content->setPublished();
            }
            */
        } catch (ClientException $e) {
            $responseBody = json_decode($e->getResponse()->getBody());
            $response['success'] = false;
            $response['error'] = $responseBody->message;
        }

        return $response;
    }

    private function createMailchimpInstance ()
    {
        $settings = json_decode($this->connection->settings);

        return new MailChimp($settings->access_token, 'oAuth2', null, $this->datacenter);
    }

    private function prepareCampaignData ()
    {
        return [
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
    }
}