<?php namespace Launch\Connections\API;

use Illuminate\Support\Facades\Config;
use GuzzleHttp\Client;

// Eh... google api lib not namespaced
use Google_Client;
use Google_Service_Oauth2;
use Google_Service_Plus;
use Google_Service_Plus_Moment as Google_Moment;
use Google_Service_Plus_ItemScope as Google_ItemScope;

class GooglePlusAPI extends GoogleAPI
{

    protected $configKey = 'services.google_plus';

    public function getMe() {
        // This gets google + plus profile info
        if (!$this->me) {
            $api = new Google_Service_Oauth2($this->getClient());
            $this->me = $api->userinfo->get();
        }

        return $this->me;
    }

    public function getIdentifier() {
        $me = $this->getMe();

        return ucwords($me->name);
    }

    public function getUrl() {
        $me = $this->getMe();

        return $me->link;
    }

    /**
     * @see https://developers.google.com/+/api/latest/moments/insert
     */
    public function postContent($content) {
        $response = ['success' => true, 'response' => []];
        try {
            $client = $this->getClient();
            
            // set $requestVisibleActions to write moments
            $requestVisibleActions = [
                'http://schema.org/CreativeWork',
                'http://schemas.google.com/AddActivity',
                'http://schema.org/Thing',
                'http://schema.org/AddAction',
                'http://schema.org/CreateAction',
                'http://schema.org/url'];

            $client->setRequestVisibleActions($requestVisibleActions);

            $service = new Google_Service_Plus($client, ['debug' => true]);

            $moment_body = new Google_Moment();
            $moment_body->setType("http://schema.org/CreateAction");

            $item_scope = new Google_ItemScope();
            $item_scope->setId("target-id-1");
            $item_scope->setType("http://schema.org/CreativeWork");
            $item_scope->setName(strip_tags($content->body));
            $item_scope->setDescription(strip_tags($content->body));
            $item_scope->setCaption(strip_tags($content->body));

            $upload = \Upload::find($content->upload_id);
            if ($upload && $upload->media_type == 'image') {
                $item_scope->setImage($upload->getUrl());
                $item_scope->setContentUrl($upload->getUrl());
            }

            $moment_body->setTarget($item_scope);
            $momentResult = $service->moments->insert('me', 'vault', $moment_body);

            $response['success'] = true;
            $response['response'] = $momentResult;
            $response['external_id'] = $momentResult->id;
        } catch (\Exception $e) {
            $response['success'] = false;
            $response['error'] = $e->getMessage();
        }

        return $response;
    }

}