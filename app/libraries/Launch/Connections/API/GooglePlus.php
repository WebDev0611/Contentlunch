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

            $service = new Google_Service_Plus($client);


            $moment_body = new Google_Moment();
            $moment_body->setType("http://schemas.google.com/CreateActivity");

            $create = new Google_ItemScope;
            $create->setId(uniqid());
            $create->setType('http://schema.org/CreativeWork');
            $create->setName($this->stripTags($content->title));
            $create->setDescription($this->stripTags($content->body));
            $create->setText($this->stripTags($content->body));
            $upload = $content->upload()->first();
            if ($upload && $upload->media_type == 'image') {
                $create->setImage($upload->getImageUrl('large'));
            }
            
            $moment_body->setTarget($create);

            $momentResult = $service->moments->insert('me', 'vault', $moment_body);

            $response['success'] = true;
            $response['response'] = $momentResult;
            $response['external_id'] = $momentResult->id;
        } catch (\Exception $e) {
            $response['success'] = false;
//      $response['response'] = $momentResult;
            $response['error'] = $e->getMessage();
        }

        return $response;
    }

}