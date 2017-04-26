<?php

namespace Connections\API;

use App\MailChimp;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

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
            /*
             NOTE: Since there's a bug in Mailchimp API, the POST method returns 500 Error code
             instead of the actual successful response when creating Campaigns. This is preventing us from fetching
             Campaign ID directly from the response, but instead we have to make a GET request to fetch all campaigns
             and then take the newest one.
            */

            // Let's create a new Campaign.
            $create = $mailChimp->post('campaigns', $this->prepareCampaignData());

            if($create['status'] = 500) {
                // Check if this is a bug
                $allCampaigns = $mailChimp->get('campaigns', [
                    'sort_field' => 'create_time',
                    'sort_dir'   => 'DESC'
                ]);

                $campaignID = $allCampaigns['campaigns'][0]['id'];
            } elseif(!empty($create['id'])) {
                $campaignID = $create['id'];
            }

            // Update the content, and add a body to it
            $updateContent = $create = $mailChimp->put('campaigns/' . $campaignID . '/content', [
                'html' => $this->content->body
            ]);

            // Determine if update succeeded
            if(!empty($updateContent['plain_text'])) {
                $response = [
                    'success'  => true,
                    'response' => [],
                ];

                $this->content->setPublished();
            }
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
            'content'          => [
                'html' => $this->content->body
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