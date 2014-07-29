<?php namespace Launch\OAuth\Service;

use Illuminate\Support\Facades\Config;

class Hubspot extends AbstractServiceOAuth2 {

  protected $baseApiUri = 'https://api.hubapi.com/';

  protected $authorizationEndpoint = 'https://app.hubspot.com/auth/authenticate/';

  protected $accessTokenEndpoint = 'https://api.hubapi.com/auth/v1/refresh';

  const SCOPE_BLOG_RW = 'blog-rw';
  const SCOPE_OFFLINE = 'offline';

  public function getAuthorizationUri(array $additionalParameters = []) {
    $portalID = \Input::get('portalid');
    $params = array_merge($additionalParameters, [
      // Add portal id
      'portalId' => $portalID
    ]);
    return parent::getAuthorizationUri($params);
  }

}