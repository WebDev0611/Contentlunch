<?php namespace Launch\OAuth\Service;

class Wordpress extends AbstractServiceOAuth2 {

  protected $baseApiUri = 'https://public-api.wordpress.com/rest/v1/me/';

  protected $authorizationEndpoint = 'https://public-api.wordpress.com/oauth2/authorize';

  protected $accessTokenEndpoint = 'https://public-api.wordpress.com/oauth2/token';

}