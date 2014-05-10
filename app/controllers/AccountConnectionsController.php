<?php

use OAuth\Common\Consumer\Credentials;
use OAuth\Common\Http\Client\StreamClient;
use OAuth\Common\Storage\Session as OAuthSession;
use OAuth\ServiceFactory;
use OAuth\OAuth2\Service\Linkedin;
use Launch\OAuth\Service\Wordpress;

class AccountConnectionsController extends BaseController {

  public function index($accountID)
  {
    if ( ! $this->inAccount($accountID)) {
      return $this->responseAccessDenied();
    }
    return AccountConnection::doQuery($accountID, Input::get('type'));
  }

  protected function setupOAuthService($provider)
  {
    // Will be different based on environment
    switch (app()->environment()) {
      case 'staging':
        $redirectURL = 'http://staging.contentlaunch.surgeforward.com/api/add-connection';
      break;
      default:
        $redirectURL = 'http://localhost:8080/api/add-connection';
        //$redirectURL = 'http://local.contentlaunch.com/api/add-connection';
    }
    $serviceConfig = Config::get('services.'. $provider);
    $credentials = new Credentials(
      $serviceConfig['key'],
      $serviceConfig['secret'],
      $redirectURL
    );
    $storage = new OAuthSession;
    $serviceFactory = new ServiceFactory;
    $serviceFactory->registerService('wordpress', 'WordpressService');
    $service = $serviceFactory->createService($provider, $credentials, $storage, $serviceConfig['scope']);
    return $service;
  }

  /**
   * Goto the provider's url for authenticating with contentlaunch
   * Based on the connection_id url param
   */
  public function create($accountID)
  {
    if ( ! $this->inAccount($accountID)) {
      return $this->responseAccessDenied();
    }
    $connection = Connection::find(Input::get('connection_id'));
    if ( ! $connection) {
      return $this->responseError("Unable to find connection");
    }
    // Set the connection type in the SESSION
    Session::set('connection_id', $connection->id);
    Session::set('account_id', $accountID);
    $service = $this->setupOAuthService($connection->provider);
    return Redirect::away( (string) $service->getAuthorizationUri());
  }

  /**
   * Connection providers should redirect to this route
   * Create a new accountConnection
   * Store the access code associated with the connection
   * Redirect to /account/connections
   */
  public function addConnection()
  {
    $accountID = Session::get('account_id');
    $connectionID = Session::get('connection_id');
    if ( ! $this->inAccount($accountID)) {
      return $this->responseAccessDenied();
    }
    $connection = Connection::find($connectionID);
    if ( ! $connection) {
      return $this->responseError("Unable to find connection");
    }
    $service = $this->setupOAuthService($connection->provider);
    $settings = [];
    switch ($connection->provider) {
      default:
        $settings = Input::all();
        $state = Input::has('state') ? Input::get('state') : null;
        $settings['token'] = $service->requestAccessToken(Input::get('code'), $state);
    }
    $connect = new AccountConnection;
    $connect->account_id = $accountID;
    $connect->connection_id = $connectionID;
    $connect->name = $connection->name;
    $connect->status = 1;
    $connect->settings = $settings;
    if ($connect->save()) {
      return Redirect::to('/account/connections');
    }
    return ['Error saving connection'];
  }

  public function show($accountID, $accountConnectID)
  {
    if ( ! $this->inAccount($accountID)) {
      return $this->responseAccessDenied();
    }
    return AccountConnection::find($accountConnectID);
  }

  public function update($accountID, $accountConnectID)
  {
    if ( ! $this->inAccount($accountID)) {
      return $this->responseAccessDenied();
    }
    $connection = AccountConnection::find($accountConnectID);
    if ($connection->updateUniques()) {
      return $this->show($accountID, $accountConnectID);
    }
    return $this->responseError($connection->errors()->all(':message'));
  }

  public function destroy($accountID, $accountConnectID)
  {
    if ( ! $this->inAccount($account_id)) {
      return $this->responseAccessDenied();
    }
    $connection = AccountConnection::find($accountConnectID);
    if ($connection->delete()) {
      return ['status' => 'OK'];
    }
    return $this->responseError("Unable to delete connection");
  }

}
