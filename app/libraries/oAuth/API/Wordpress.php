<?php 
namespace oAuth\API;

//use Illuminate\Support\Facades\Config;
use Config;
use Crypt;

class WordPressAuth
{

    protected $authorizationEndpoint = 'https://public-api.wordpress.com/oauth2/authorize';

    protected $accessTokenEndpoint = 'https://public-api.wordpress.com/oauth2/token';

     public function __construct()
    {
    }

    public function getToken($content){
        $curl = curl_init( $this->accessTokenEndpoint );
        curl_setopt( $curl, CURLOPT_POST, true );
        curl_setopt( $curl, CURLOPT_POSTFIELDS, array(
            'client_id' => Config::get('services.wordpress.client_id'),
            'client_secret' => Config::get('services.wordpress.client_secret'),
            'grant_type' => 'password',
            'username' => $content->connection->getSettings()->username,
            'password' =>  Crypt::decrypt($content->connection->getSettings()->password),
        ) );
        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1);
        $auth = curl_exec( $curl );
        $auth = json_decode($auth);
        $access_key = $auth->access_token;
        return $access_key;
    }    

}