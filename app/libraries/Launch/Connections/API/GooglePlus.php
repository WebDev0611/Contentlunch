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

            $service = new Google_Service_Plus($client, ['debug' => true]);

            $moment_body = new Google_Moment();
            //$moment_body->setType("http://schemas.google.com/CreateActivity");
            $moment_body->setType("http://schema.org/AddAction");

/*
            $create = new Google_ItemScope;
            $create->setId(uniqid());
            $create->setType('http://schema.org/CreativeWork');
            $create->setName('Test 123');
            $create->setDescription('This is a test post');
            //$create->setCaption('This is a test post');
            //$create->setImage('http://th00.deviantart.net/fs70/PRE/i/2012/135/a/7/tux_button_by_blacklite_teh_haxxor-d4zv3fv.png');
            $create->setText('Check out this tux image');
            //$create->setUrl('http://imgur.com');

            */

            $create = new Google_ItemScope;
            $create->setId(uniqid());
            $create->setType('http://schema.org/Thing');
            $create->setName('Spitz seeds are the best!');
            $create->setDescription('I\'m totally addicted to these things');

            $moment_body->setTarget($create);


            /*

            //$moment_body->setType("http://schemas.google.com/AddActivity");
            $moment_body->setType("http://schemas.google.com/CreateActivity");
            $moment_body->setName('Test 123');
            $moment_body->setText('Hello this is a post');
            //$moment_body->setType("http://schema.org/AddAction");
            $item_scope = new Google_ItemScope();
            $item_scope->setId(uniqid());
            //$item_scope->setType("http://schema.org/AddAction");
            $item_scope->setType("http://schema.org/Thing");
            $item_scope->setName($content->title);
            //$item_scope->setText(strip_tags($content->body));
            //$item_scope->setUrl('http://contentlaunch.com');

            $result = new Google_ItemScope;
            $result->setId(uniqid());
            $result->setType("http://schema.org/CreativeWork");
            $result->setName($content->title);
            $result->setText("This is the body of my post");

            $upload = $content->upload()->first();
            if ($upload && $upload->media_type == 'image') {
                $item_scope->setImage($upload->getUrl());
            }
            //$moment_body->setTarget($item_scope);
            //$moment_body->setTarget($result);
            */
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