<?php namespace Launch\OAuth\Service;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;

use OAuth\Common\Consumer\Credentials;
use OAuth\Common\Http\Client\StreamClient;
use OAuth\Common\Storage\Session as OAuthSession;
use OAuth\ServiceFactory as OAuthServiceFactory;
use OAuth\OAuth2\Service\Linkedin;
use Launch\OAuth\Service\Wordpress;

/**
 * Creates OAuth services
 */
class ServiceFactory {

  protected $config = [];

  protected $provider;

  protected $service;

  protected $storage;

  public function __construct($provider)
  {
    $this->provider = $provider;
    $this->config = Config::get('services.'. $this->provider);
    // Will be different based on environment
    
    switch (app()->environment()) {
      case 'staging':
        $redirectURL = 'http://staging.contentlaunch.surgeforward.com/api/add-connection';
      break;
      default:
        $redirectURL = $this->config['callback_domain'] .'/api/add-connection';   
    }
    $credentials = new Credentials(
      $this->config['key'],
      $this->config['secret'],
      $redirectURL
    );
    $this->storage = new OAuthSession;
    $serviceFactory = new OAuthServiceFactory;
    $serviceFactory->registerService('wordpress', 'WordpressService');
    $serviceFactory->registerService('salesforce', 'SalesforceService');
    $serviceFactory->registerService('hubspot', 'HubspotService');
    switch ($this->provider) {
      case 'twitter':
        // OAuth1
        $this->service = $serviceFactory->createService($this->provider, $credentials, $this->storage);
      break;
      default:
        // OAuth2
        $this->service = $serviceFactory->createService($this->provider, $credentials, $this->storage, $this->config['scope']);
    }
  }

  public function getAuthorizationUri()
  {
    switch ($this->provider) {
      case 'twitter':
        $token = $this->service->requestRequestToken();
        return (string) $this->service->getAuthorizationUri([
          'oauth_token' => $token->getRequestToken()
        ]);
      break;
      default:
        return (string) $this->service->getAuthorizationUri();
    }
  }

  public function getCallbackData($input = null)
  {
    if ( ! $input) {
      $input = Input::all();
    }
    $data = $input;
    switch ($this->provider) {
      case 'twitter':
        // OAuth1
        $token = $this->storage->retrieveAccessToken('Twitter');
        $data['token'] = $this->service->requestAccessToken($input['oauth_token'], $input['oauth_verifier'], $token->getRequestTokenSecret());
      break;
      case 'google':
        $data['token'] = $this->service->requestAccessToken($input['code']);
      break;
      case 'hubspot':
        $data['token'] = $input['access_token'];
      break;
      default:
        // OAuth2
        $state = isset($input['state']) ? $input['state'] : null;
        $data['token'] = $this->service->requestAccessToken($input['code'], $state);
    }
    return $data;
  }

}