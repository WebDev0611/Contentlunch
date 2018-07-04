<?php 
namespace oAuth\API;

//use Illuminate\Support\Facades\Config;
use Config;
use Crypt;

class FacebookAuth
{

    public function getToken($content=null){

/*
    - if content null get auth user and pull user token from db
    */
        return ($content ? $content->connection->getSettings()->token : '');
    }    

}