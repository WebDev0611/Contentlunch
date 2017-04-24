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
    public function createPost ()
    {
        return $this->createCampaign();
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
            $create = $mailChimp->post('campaigns', $this->prepareCampaignData());

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

    public function getLists ()
    {
        $mailChimp = $this->createMailchimpInstance();

        return $mailChimp->get('lists');
    }

    private function createMailchimpInstance ()
    {
        $settings = json_decode($this->connection->settings);

        return new MailChimp($settings->access_token, 'oAuth2', null, $this->datacenter);
    }

    private function prepareCampaignData ()
    {
        $contentMailchimpSettings = json_decode($this->content->mailchimp_settings);

        return [
            'type'             => 'regular', // accepted values: regular, plaintext, absplit, rss, variate
            'recipients'       => [
                'list_id' => $contentMailchimpSettings->list
            ],
            'settings'         => [
                'subject_line' => $this->content->email_subject,
                'title'        => $this->content->title,
                'from_name'    => $contentMailchimpSettings->from_name,
                'reply_to'     => $contentMailchimpSettings->reply_to
            ],
            'variate_settings' => [
                'winner_criteria' => 'opens'    // Possible Values: opens clicks manual total_revenue
            ],
            'rss_opts'         => [
                'feed_url'  => $contentMailchimpSettings->feed_url,
                'frequency' => 'daily'  // Possible Values: daily weekly monthly
            ]
        ];
    }

    private function prepareCampaignContentData ()
    {
        return [
            'html' => $this->content->body
        ];
    }
}