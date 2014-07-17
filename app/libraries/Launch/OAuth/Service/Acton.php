<?php namespace Launch\OAuth\Service;

use Illuminate\Support\Facades\Config;

class Acton extends AbstractServiceOAuth2 {

  protected $baseApiUri = 'https://restapi.actonsoftware.com/';

  protected $authorizationEndpoint = 'https://restapi.actonsoftware.com/authorize';

  protected $accessTokenEndpoint = 'https://restapi.actonsoftware.com/token';

}