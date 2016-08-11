<?php 
namespace Connections\API;

use Illuminate\Support\Facades\Config;
use Facebook\Facebook;

class FacebookAPI
{
    // - dunno if needed
    protected $configKey = 'facebook';

     public function __construct($content=null)
    {
            $this->client = null;
            $this->content = $content;
    }

    // Instantiate a new facebook Service using USER token
    // -- https://developers.facebook.com/docs/php/Facebook/5.0.0
    public function getClient()
    {
        if (!$this->client) {
           
                $this->client = new Facebook ([
                    'app_id' => Config::get('services.facebook.client_id'), // content launch app id
                    'app_secret' => Config::get('services.facebook.client_secret'), // content launch secret id
                    'default_graph_version' => 'v2.5',
                    'default_access_token' =>  $this->content->connection->getSettings()->page_token
                ]);
            
        }
        return $this->client;
    }

   /* public function getUserToken($provider) 
    {
        $user = $this->getUser($provider);
        return $user->token;
    }

    public function getAccounts()
    {
        $client = $this->getClient();
        // Get Account List
        $response = $client->get('/me/accounts'); 
        return  $response->getGraphEdge();
    }

    public function getLongLivedAccessToken($user)
    {
        $client = $this->getClient();
        $oAuth2Client = $client->getOAuth2Client();
        $accessToken = $oAuth2Client->getLongLivedAccessToken($user->token);
        //$dateToken = $accessToken->getExpiresAt();
        return (string)  $accessToken;
    }*/


    public function createPost()
    {
            $content = $this->content;
            // - standardize return 
            $response = ['success' => false, 'response' => []];
            try {
                   
                    // Compile data
                    $postdata = [
                            'message' =>  $content->title . ' - '. $content->body
                    ];

                    $client = $this->getClient();
                    $page_id = $content->connection->getSettings()->page_id;
                    $response = $client->post('/'.$page_id.'/feed', $postdata);


                    $response = [
                        'success'        => true,
                        'response'      => json_decode( $response->getGraphNode() )
                    ];   
            } catch(\Facebook\Exceptions\FacebookResponseException $e) {
                    $responseBody = json_decode($e->getMessage());
                    $response['success'] = false;
                    $response['error'] = 'Graph returned an error:'. $responseBody;
            } catch(\Facebook\Exceptions\FacebookSDKException $e) {
                    $responseBody = json_decode($e->getMessage());
                    $response['success'] = false;
                    $response['error'] = 'Facebook SDK returned an error: ' . $responseBody;
            } 

            return $response;
    }
}