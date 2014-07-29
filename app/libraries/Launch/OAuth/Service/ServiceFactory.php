<?php namespace Launch\OAuth\Service;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;

use OAuth\Common\Consumer\Credentials;
use OAuth\Common\Http\Client\StreamClient;
use OAuth\Common\Storage\Session as OAuthSession;
use OAuth\ServiceFactory as OAuthServiceFactory;
use OAuth\OAuth2\Service\Linkedin;
use Launch\OAuth\Service\Wordpress;
use Launch\OAuth\Service\Acton;

/**
 * Creates OAuth services
 */
class ServiceFactory {

  protected $config = [];

  protected $provider;

  public $service;

  protected $storage;

  public function __construct($provider)
  {
    switch ($provider) {
     // case 'soundcloud':
        //$this->provider = 'soundCloud';
        //$this->config = Config::get('services.soundcloud');
      //break;
      case 'youtube':
        // Youtube = google
        $this->provider = 'google';
        $this->config = Config::get('services.youtube');
      break;
      default:
        $this->provider = $provider;
        $this->config = Config::get('services.'. $provider);
    }
    
    $redirectURL = 'https://staging.contentlaunch.surgeforward.com/api/add-connection';
    $credentials = new Credentials(
      $this->config['key'],
      $this->config['secret'],
      $redirectURL
    );
    $this->storage = new OAuthSession;
    $serviceFactory = new OAuthServiceFactory;
    $serviceFactory->registerService('acton', 'ActonService');
    $serviceFactory->registerService('wordpress', 'WordpressService');
    $serviceFactory->registerService('salesforce', 'SalesforceService');
    $serviceFactory->registerService('soundcloud', 'SoundcloudService');
    $serviceFactory->registerService('hubspot', 'HubspotService');
    switch ($this->provider) {
      case 'tumblr':
      case 'twitter':
        // OAuth1
        $this->service = $serviceFactory->createService($this->provider, $credentials, $this->storage);
      break;
      default:
        // OAuth2
        $scope = $this->config['scope'];
        if ($scope) {
          $this->service = $serviceFactory->createService($this->provider, $credentials, $this->storage, $this->config['scope']);  
        } else {
          $this->service = $serviceFactory->createService($this->provider, $credentials, $this->storage);
        }
    }
  }

  public function getAuthorizationUri()
  {
    switch ($this->provider) {
      case 'tumblr':
      case 'twitter':
        $token = $this->service->requestRequestToken();
        return (string) $this->service->getAuthorizationUri([
          'oauth_token' => $token->getRequestToken()
        ]);
      break;
      case 'google': // youtube, g+, google docs
        // Request offline access token
        // @see https://developers.google.com/accounts/docs/OAuth2WebServer#offline
        return (string) $this->service->getAuthorizationUri([
          'access_type' => 'offline',
          'approval_prompt' => 'force'
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
      case 'tumblr':
        $token = $this->storage->retrieveAccessToken('Tumblr');
        $data['token'] = $this->service->requestAccessToken($input['oauth_token'], $input['oauth_verifier'], $token->getRequestTokenSecret());
      break;
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
        $data['refresh_token'] = $input['refresh_token'];
      break;
      default:
        // OAuth2
        $state = isset($input['state']) ? $input['state'] : null;
        $data['token'] = $this->service->requestAccessToken($input['code'], $state);
    }
    return $data;
  }

}