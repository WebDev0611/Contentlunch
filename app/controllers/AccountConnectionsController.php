<?php

use Launch\OAuth\Service\ServiceFactory;
use Launch\Connections\API\ConnectionConnector;

class AccountConnectionsController extends BaseController {

  public function index($accountID)
  {
    if ( ! $this->inAccount($accountID)) {
      return $this->responseAccessDenied();
    }
    return AccountConnection::doQuery($accountID, Input::get('type'), Input::get('provider'));
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
    $service = new ServiceFactory($connection->provider);
    $settings = $service->getCallbackData();
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
    if ( ! $this->inAccount($account_id)) {
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

    $friends   = Input::get('friends');
    $message   = Input::get('message');
    $contentID = Input::get('contentId');

    $connectionData = $this->show($accountID, $connectionID);
    $connectionApi = ConnectionConnector::loadAPI($connectionData->connection->provider, $connectionData);

    return $connectionApi->sendDirectMessage($friends, $message, $contentID);
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
    $contentID = Input::get('contentId');

    $linkedIn = ConnectionConnector::loadAPI($connectionData->connection->provider, $connectionData);

    return $linkedIn->sendMessageToGroup($group, $message, $contentID);
  }
}
