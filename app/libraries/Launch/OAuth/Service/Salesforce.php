<?php namespace Launch\OAuth\Service;

class Salesforce extends AbstractServiceOAuth2 {

  protected $baseApiUri = 'https://public-api.wordpress.com/rest/v1/me/';

  protected $authorizationEndpoint = 'https://login.salesforce.com/services/oauth2/authorize';

  protected $accessTokenEndpoint = 'https://na1.salesforce.com/services/oauth2/token';

  const SCOPE_API = 'api';

}