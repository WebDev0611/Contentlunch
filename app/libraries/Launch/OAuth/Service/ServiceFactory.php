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
      case 'google-drive':
        $this->config = Config::get('services.google_drive');
      break;
      case 'google-plus':
        $this->config = Config::get('services.google_plus');
      break;
      default:
        $this->config = Config::get('services.'. $provider);
    }
    $this->provider = $provider;
    
    $redirectURL = Config::get('services.redirect_url');
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
    $serviceFactory->registerService('vimeo', 'VimeoService');
    switch ($this->provider) {
      case 'blogger':
      case 'google':
      case 'google-drive':
      case 'google-plus':
      case 'youtube':
        // Use google oauth
        $scope = $this->config['scope'];
        if ($scope) {
          $this->service = $serviceFactory->createService('google', $credentials, $this->storage, $this->config['scope']);  
        } else {
          $this->service = $serviceFactory->createService('google', $credentials, $this->storage);
        }
      break;
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
    // Params to add the auth uri
    $params = [];
    switch ($this->provider) {
      case 'dropbox':
        $params['force_reapprove'] = 'true';
      break;
      case 'facebook':
        $params['auth_type'] = 'reauthenticate';
      break;
      case 'tumblr':
        $token = $this->service->requestRequestToken();
        $params['oauth_token'] = $token->getRequestToken();
      break;
      case 'twitter':
        $token = $this->service->requestRequestToken();
        $params['oauth_token'] = $token->getRequestToken();
        $params['force_login'] = 'true';
      break;
      case 'google-plus':
        // Needed for creating moments
        $params['request_visible_actions'] = 'http://schemas.google.com/AddActivity http://schemas.google.com/CreateActivity http://schema.org/CreativeWork http://schema.org/Thing'; //urlencode('http://schemas.google.com/AddActivity');
      case 'google': // youtube, g+, google docs
      case 'google-drive':
      case 'google-plus':
      case 'youtube':
      case 'blogger':
        $this->service->setAccessType('offline');
        $params['approval_prompt'] = 'force';
      break;
    }
    return (string) $this->service->getAuthorizationUri($params);
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