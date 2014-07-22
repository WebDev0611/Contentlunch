<?php namespace Launch\Connections\API;

use Illuminate\Support\Facades\Config;

abstract class AbstractConnection {

  protected $accountConnection;

  protected $config = [];

  protected $client = null;

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

  abstract protected function getClient();

  abstract protected function getIdentifier();

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