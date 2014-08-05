<?php namespace Launch\OAuth\Service;

class Vimeo extends AbstractServiceOAuth2 {

  protected $baseApiUri = 'https://api.vimeo.com';

  protected $authorizationEndpoint = 'https://api.vimeo.com/oauth/authorize';

  protected $accessTokenEndpoint = 'https://api.vimeo.com/oauth/access_token';

  const API_PUBLIC = 'public';
  const API_PRIVATE = 'private';
  const API_UPLOAD = 'upload';
}