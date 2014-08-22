<?php namespace Launch\Connections\API;

use Launch\OAuth\Service\ServiceFactory;
use Illuminate\Support\Facades\Config;

abstract class AbstractConnection {

  protected $accountConnection;

  protected $config = [];

  protected $client = null;

  protected $me = null;

  // Not applicable text
  const NA_TEXT = 'n/a';

  public function __construct(array $accountConnection)
  {
    $this->accountConnection = $accountConnection;
    $this->config = Config::get($this->configKey);
  }

  public function getAccessToken()
  {
    // If no access token, it's impossible for us to
    // use this connection
    if (empty($this->accountConnection['settings']['token'])) {
      throw new \Exception("Invalid connection");
    }
    if ( ! is_object($this->accountConnection['settings']['token'])) {
      return;
    }
    $token = $this->accountConnection['settings']['token'];

    $expire = $token->getEndOfLife();
    if ($token->getRefreshToken() && $expire < time()) {
      // This token has expired, get refresh token
      // Load up the service class
      $factory = new ServiceFactory($this->accountConnection['connection']['provider']);
      $newToken = $factory->service->refreshAccessToken($token);
      if ($newToken) {
        // Save new token, this implies that the provider
        // sends back a new refresh token
        $connectionObject = \AccountConnection::find($this->accountConnection['id']);
        $settings = unserialize($connectionObject->settings);
        $settings['token'] = $newToken;
        // If new token doesn't have a refresh token, copy over
        // the original refresh token (provider uses original refresh token for
        // subsequent refresh calls)
        if ( ! $newToken->getRefreshToken()) {
          $newToken->setRefreshToken($token->getRefreshToken());
        }
        // Copy over extra params
        $newToken->setExtraParams($token->getExtraParams());
        $connectionObject->settings = $settings;
        $connectionObject->updateUniques();
        $this->accountConnection['settings']['token'] = $newToken;
      }
    }

    $token = $this->accountConnection['settings']['token']->getAccessToken();
    if ( ! $token) {
      throw new \Exception("Invalid connection");
    }

    return $token;
  }

  public function getAccessTokenSecret()
  {
    if (empty($this->accountConnection['settings']['token'])) {
      throw new \Exception("Invalid connection");
    }
    return $this->accountConnection['settings']['token']->getAccessTokenSecret();
  }

  /**
   * Setup the connection client with valid access token
   */
  abstract protected function getClient();

  /**
   * Get the external user / account id
   */
  abstract public function getExternalId();

  /**
   * Get the me name / identifier string of connected account
   */
  abstract public function getIdentifier();

  /**
   * Get the me data for the connected account
   */
  abstract public function getMe();

  /**
   * Get the url of the connected account
   */
  abstract public function getUrl();

  /**
   * Save connection identifer in the accountConnection record
   */
  protected function saveIdentifier($identifier)
  {
    $connection = AccountConnection::find($this->accountConnection['id']);
    $settings = unserialize($connection->settings);
  }

  abstract public function postContent($content);

  public function isValid()
  {
    try {
      $token = $this->getAccessToken();
    } catch (\Exception $e) {

    }
    return ! empty($token);
  }

}