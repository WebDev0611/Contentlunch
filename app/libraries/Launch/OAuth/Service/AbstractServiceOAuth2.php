<?php namespace Launch\OAuth\Service;

use OAuth\OAuth2\Service\AbstractService;
use OAuth\OAuth2\Token\StdOAuth2Token;
use OAuth\Common\Http\Exception\TokenResponseException;
use OAuth\Common\Http\Uri\Uri;

class AbstractServiceOAuth2 extends AbstractService {

  protected $baseApiUri;

  protected $authorizationEndpoint;

  protected $accessTokenEndpoint;

  public function __construct(
    $credentials,
    $httpClient,
    $storage,
    $scopes = [],
    $baseApiUri = null
  ) {

    // These properties should be set on extending classes
    $abstractProperties = ['baseApiUri', 'authorizationEndpoint', 'accessTokenEndpoint'];
    foreach ($abstractProperties as $property) {
      if (empty($this->$property)) {
        throw new \Exception("Service class property not set: $property");
      }
    }

    parent::__construct($credentials, $httpClient, $storage, $scopes, $baseApiUri, true);

    if (null === $baseApiUri) {
      $this->baseApiUri = new Uri($this->baseApiUri);
    }

  }

  /**
   * {@inheritdoc}
   */
  public function getAuthorizationEndpoint()
  {
    return new Uri($this->authorizationEndpoint);
  }

  /**
   * {@inheritdoc}
   */
  public function getAccessTokenEndpoint()
  {
    return new Uri($this->accessTokenEndpoint);
  }

  /**
   * {@inheritdoc}
   */
  protected function getAuthorizationMethod()
  {
    return static::AUTHORIZATION_METHOD_QUERY_STRING_V2;
  }

  /**
   * {@inheritdoc}
   */
  protected function parseAccessTokenResponse($responseBody)
  {
    $data = json_decode($responseBody, true);
    if (null === $data || ! is_array($data)) {
      throw new TokenResponseException('Unable to parse response.');
    } elseif (isset($data['error'])) {
      throw new TokenResponseException('Error in retrieving token: "'. $data['error'] .'"');
    }

    $token = new StdOAuth2Token();
    $token->setAccessToken($data['access_token']);
    $token->setExtraParams($data);
    return $token;
  }

}