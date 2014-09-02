<?php

use Launch\OAuth\Service\ServiceFactory;
use Launch\Connections\API\ConnectionConnector;
use Launch\Exception\OAuthTokenException;

class AccountConnectionsController extends BaseController {

  public function index($accountID)
  {
    if ( ! $this->inAccount($accountID)) {
      return $this->responseAccessDenied();
    }
    $connections = AccountConnection::doQuery($accountID, Input::get('type'), Input::get('provider'));
    if ($connections) {
      foreach ($connections as $connection) {
        switch ($connection->connection_provider) {
          case 'google-drive':
            $className = 'GoogleDriveAPI';
          break;
          case 'google-plus':
            $className = 'GooglePlusAPI';
          break;
          case 'linkedin':
            $className = 'LinkedInAPI';
          break;
          default:
            $className = ucwords($connection->connection_provider) .'API';
        }
        $class = 'Launch\\Connections\\API\\' . $className;
        if (class_exists($class)) {
          $api = new $class((array) $connection);

          // The connection identifier should be set when the 
          // connection is created.
          // This is just a fix to get identifiers
          // for existing connections
          if ( ! $connection->identifier && $api->isValid()) {
            try {
              $connection->identifier = $api->getIdentifier();
              DB::table('account_connections')
                ->where('id', $connection->id)
                ->update([
                  'identifier' => $connection->identifier
                ]);
              } catch (\Exception $e) {
              
              }
          }
          if ( ! $connection->url && $api->isValid()) {
            try {
              $connection->url = $api->getUrl();
              DB::table('account_connections')
                ->where('id', $connection->id)
                ->update([
                  'url' => $connection->url
                ]);
            } catch (\Exception $e) {

            }
          }
        }
      }
    }

    return $connections;
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
    // Slideshare doesn't do OAuth, passing along credentials
    if ($connection->provider == 'slideshare') {
      $connect = new AccountConnection;
      $connect->account_id = $accountID;
      $connect->connection_id = $connection->id;
      $connect->name = $connection->name;
      $connect->status = 1;
      $connect->settings = [
        'username' => Input::get('username'),
        'password' => Input::get('password')
      ];
      $service = new ServiceFactory($connection->provider);
      $api = ConnectionConnector::loadAPI($connection->provider, $connect);
      // Get me data, which serves as a username/password check
      $data = $api->getMe();
    }
    // Set the connection type in the SESSION
    Session::put('connection_id', $connection->id);
    Session::put('account_id', $accountID);
    $service = new ServiceFactory($connection->provider);
    return Redirect::away($service->getAuthorizationUri());
  }

  /**
   * Connection providers should redirect to this route
   * Create a new accountConnection
   * Store the access code associated with the connection
   * Redirect to /account/connections
   */
  public function addConnection()
  {
    // As far as I (CWSpear) know, most services only allow 
    // one callback URL (usually for security reasons), so
    // we're using a session variable to "point" this return
    // call to different places as needed
    $action = Session::get('action');
    Session::forget('action');
    if ($action) {
      switch($action) {
        case 'finish_guest': return GuestCollaboratorsController::finishGuest(); break;
        case 'finish_group': return GuestCollaboratorsController::finishGroup(); break;
      }
    }

    $accountID = Session::get('account_id');
    $connectionID = Session::get('connection_id');
    if ( ! $this->inAccount($accountID)) {
      return $this->responseAccessDenied();
    }
    $connection = Connection::find($connectionID);
    if ( ! $connection) {
      return $this->responseError("Unable to find connection");
    }
    // Can't have more than 1 hubspot connection
    if ($connection->provider == 'hubspot') {
      AccountConnection::where('account_id', $accountID)
        ->where('connection_id', $connection->id)
        ->delete();
    }
    try {
      $service = new ServiceFactory($connection->provider);
      $settings = $service->getCallbackData();
    } catch (\Exception $e) {
      // User canceled the connection?
      if ($connection->type == 'promote') {
        return Redirect::to('/account/promote');
      } else {
        return Redirect::to('/account/connections');
      }
    }
    $connect = new AccountConnection;
    $connect->account_id = $accountID;
    $connect->connection_id = $connectionID;
    $connect->name = $connection->name;
    $connect->status = 1;
    $connect->settings = $settings;
    $connect->updated_at = time();
    
    // Load up the connection API, check if this specific connection account 
    // already exists for this content launch account
    $api = ConnectionConnector::loadAPI($connection->provider, $connect); 
    $externalId = $api->getExternalId();
    $connect->external_id = $externalId;
    if ($externalId) {
      $existing = AccountConnection::where('account_id', $accountID)
        ->where('external_id', $externalId)
        ->first();
    } else {
      $existing = false;
    }
    $success = false;
    if ($existing) {
      // Update this connection with new tokens
      $existing->settings = $settings;
      if ($existing->updateUniques()) {
        $success = true;
      }
    } else {
      if ($connect->save()) {
        $success = true;
      }
    }
    
    if ($success) {
      if ($connection->type == 'promote') {
          return Redirect::to('/account/promote');
      } else {
          // If we updated an existing connection,
          // the front end needs to display a message
          // to the user
          if ($existing) {
            return Redirect::to('/account/connections?updated=' . $existing->id);
          } else {
            return Redirect::to('/account/connections');
          }
      }
    }
    
    return ['Error saving connection'];
  }

  public function show($accountID, $accountConnectID)
  {
    if ( ! $this->inAccount($accountID)) {
      return $this->responseAccessDenied();
    }
    return AccountConnection::with('connection')->find($accountConnectID);
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
    if ( ! $this->inAccount($accountID)) {
      return $this->responseAccessDenied();
    }
    $connection = AccountConnection::find($accountConnectID);
    if ($connection->delete()) {
      return ['status' => 'OK'];
    }
    return $this->responseError("Unable to delete connection");
  }

  // Actions
  // -------------------------
  public function actionRouter($accountID, $connectionID, $action)
  {
    // turn dash-cash into camelCase
    $action = str_replace(' ', '', ucwords(str_replace('-', ' ', $action)));
    $action[0] = strtolower($action[0]);

    if (method_exists($this, $action)) return $this->{$action}($accountID, $connectionID);
    else return $this->responseError("Action {$action} does not exist", 404);
  }

  public function updateStats($accountID, $accountConnectionID) {
      //todo generalize beyond twitter

      $connectionData = $this->show($accountID, $accountConnectionID);
      $connectionApi = ConnectionConnector::loadAPI($connectionData->connection->provider, $connectionData);
      return $connectionApi->updateStats($accountConnectionID);
  }

  private function friends($accountID, $connectionID)
  {
    if (!$this->inAccount($accountID)) {
      return $this->responseAccessDenied();
    }

    if (!Request::isMethod('get')) return $this->responseError('friends action only accepts GET requests');

    $page = Input::get('page');
    if (!$page) $page = 0;
    $perPage = Input::get('perPage');
    if (!$perPage) $perPage = 1000;

    $connectionData = $this->show($accountID, $connectionID);
    $connectionApi = ConnectionConnector::loadAPI($connectionData->connection->provider, $connectionData);
    return $connectionApi->getFriends($page, $perPage);
  }

  private function message($accountID, $connectionID)
  {
    if (!$this->inAccount($accountID)) {
      return $this->responseAccessDenied();
    }

    if (!Request::isMethod('post')) return $this->responseError('message action only accepts POST requests');

    $friends     = Input::get('friends');
    $message     = Input::get('message');
    $contentID   = Input::get('content_id');
    $contentType = rtrim(Input::get('content_type'), 's');

    $connectionData = $this->show($accountID, $connectionID);
    $connectionApi = ConnectionConnector::loadAPI($connectionData->connection->provider, $connectionData);

    return $connectionApi->sendDirectMessage($friends, $message, $contentID, $contentType, $accountID);
  }

  // only applies to Twitter. returns length of t.co link shortener
  private function twitterLinkLength($accountID, $connectionID)
  {
    if (!$this->inAccount($accountID)) {
      return $this->responseAccessDenied();
    }

    if (!Request::isMethod('get')) return $this->responseError('twitter-link-length action only accepts GET requests');

    $connectionData = $this->show($accountID, $connectionID);
    if ($connectionData->connection->provider != 'twitter') return $this->responseError('twitter-link-length action is only valid if the provider is twitter');

    $twitter = ConnectionConnector::loadAPI($connectionData->connection->provider, $connectionData);

    return $twitter->getLinkLength();
  }

  private function groups($accountID, $connectionID)
  {
    if (!$this->inAccount($accountID)) {
      return $this->responseAccessDenied();
    }

    if (!Request::isMethod('get')) return $this->responseError('groups action only accepts GET requests');

    $connectionData = $this->show($accountID, $connectionID);
    if ($connectionData->connection->provider != 'linkedin') return $this->responseError('groups action is only valid if the provider is linkedin');

    $linkedIn = ConnectionConnector::loadAPI($connectionData->connection->provider, $connectionData);

    $page = Input::get('page');
    if (!$page) $page = 0;
    $perPage = Input::get('perPage');
    if (!$perPage) $perPage = 1000;

    return $linkedIn->getGroups($page, $perPage);
  }

  private function messageGroup($accountID, $connectionID)
  {
    if (!$this->inAccount($accountID)) {
      return $this->responseAccessDenied();
    }

    if (!Request::isMethod('post')) return $this->responseError('message-group action only accepts POST requests');

    $connectionData = $this->show($accountID, $connectionID);
    if ($connectionData->connection->provider != 'linkedin') return $this->responseError('message-group action is only valid if the provider is linkedin');

    $group     = Input::get('group');
    $message   = Input::get('message');
    $contentID = Input::get('content_id');
    $contentType = rtrim(Input::get('content_type'), 's');

    $linkedIn = ConnectionConnector::loadAPI($connectionData->connection->provider, $connectionData);

    return $linkedIn->sendMessageToGroup($group, $message, $contentID, $contentType, $accountID);
  }

  /**
   * Get available authors from connection service
   * if it is supported
   */
  private function authors($accountID, $connectionID)
  {
    if ( ! $this->inAccount(($accountID))) {
      return $this->responseAccessDenied();
    }
    $connection = $this->show($accountID, $connectionID);
    switch ($connection->connection->provider) {
      case 'hubspot':
        $api = ConnectionConnector::loadAPI($connection->connection->provider, $connection);
        $response = $api->getAuthors();
        return $response;
      break;
      default:
        return $this->responseError('Provider '. $connection->connection->provider .' does not support authors method');
    }
  }

  /**
   * Get available templates from connection service
   * if it is supported
   */
  private function templates($accountID, $connectionID)
  {
    if ( ! $this->inAccount(($accountID))) {
      return $this->responseAccessDenied();
    }
    $connection = $this->show($accountID, $connectionID);
    switch ($connection->connection->provider) {
      case 'hubspot':
        $api = ConnectionConnector::loadAPI($connection->connection->provider, $connection);
        $response = $api->getTemplates();
        return $response;
      break;
      default:
        return $this->responseError('Provider '. $connection->connection->provider .' does not support authors method');
    }
  }

  /**
   * Check the status of a connection to determine
   * if access token is still valid
   */
  private function status($accountID, $connectionID)
  {
    if ( ! $this->inAccount(($accountID))) {
      return $this->responseAccessDenied();
    }
    $connection = $this->show($accountID, $connectionID);
    $api = ConnectionConnector::loadAPI($connection->connection->provider, $connection);
    $connection->external_id = $api->getExternalId();
    $connection->updateUniques();
    // Check status by getting the me data
    try {
      $response = $api->getMe(true);
      $response = (array) $response;
      switch ($connection->connection->provider) {
        case 'facebook':
          $response = array_pop($response);
        break;
        case 'hubspot':
          $return = [];
          foreach ($response as $setting) {
            if ($setting['name'] == 'readOnly') {
              foreach ($setting['value'] as $rSetting) {
                $return[$rSetting['name']] = $rSetting['value'];
              }
            }
          }
          $response = $return;
        break;
      }
      $success = 1;
    } catch (OAuthTokenException $e) {
      $response = "Invalid access token. Please reauthenticate this connection.";
      if (Config::get('app.debug')) {
        $response .= '<br />Debug message: '. $e->getMessage();
      }
      $success = 0;
    } catch (\Exception $e) {
      $response = $e->getMessage();
      $success = 0;
    }
    return [$success, $response];
  }

}
