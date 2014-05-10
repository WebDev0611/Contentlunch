<?php

use Launch\OAuth\Service\ServiceFactory;

class AccountConnectionsController extends BaseController {

  public function index($accountID)
  {
    if ( ! $this->inAccount($accountID)) {
      return $this->responseAccessDenied();
    }
    return AccountConnection::doQuery($accountID, Input::get('type'));
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
