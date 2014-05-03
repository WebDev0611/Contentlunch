<?php

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
    // Which connection are we adding?
    $connection = Connection::find(Input::get('connection_id'));
    if ( ! $connection) {
      return $this->responseError("Unable to find connection");
    }
    // Url the provider will redirect back to
    $redirectURL = 'http://local.contentlaunch.com/api/account/'. $accountID .'/add-connection/'. $connection->id;
    switch ($connection->provider) {
      case 'linkedin':
        $params = [
          'response_type' => 'code',
          'client_id' => Config::get('app.connections.linkedin.api_key'),
          'state' => uniqid(),
          'redirect_uri' => $redirectURL
        ];
        $url = 'https://www.linkedin.com/uas/oauth2/authorization?'. http_build_query($params);
        return Redirect::to($url);
      break;
    }
    /*
    case 'HUBSPOT':
          url = 'https://app.hubspot.com/auth/authenticate/?client_id=' + launch.config.HUBSPOT_API_KEY +
            '&portalId=' + '175282' + // TODO: HOW DO WE USE THIS PORTAL ID???
            '&redirect_uri=' + encodeURI('http://local.contentlaunch.cself.loggedInUser.account.idom/account/connections');
          break;
        case 'WORDPRESS':
          url = 'https://public-api.wordpress.com/oauth2/authorize?client_id=' + launch.config.WORDPRESS_API_KEY +
            '&redirect_uri=' + encodeURIComponent('http://local.contentlaunch.com/account/connections') + '&response_type=code';
    */
  }

  /**
   * Connection providers should redirect to this route
   * Create a new accountConnection
   * Store the access code associated with the connection
   * Redirect to /account/connections
   */
  public function addConnection($accountID, $connectionID)
  {
    if ( ! $this->inAccount($accountID)) {
      return $this->responseAccessDenied();
    }
    $connection = Connection::find($connectionID);
    if ( ! $connection) {
      return $this->responseError("Unable to find connection");
    }
    $settings = [];
    switch ($connection->provider) {
      case 'linkedin':
        $settings['code'] = Input::get('code');
        $settings['state'] = Input::get('state');
      break;
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
    return array('Error saving connection');
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
      return array('status' => 'OK');
    }
    return $this->responseError("Unable to delete connection");
  }

}
