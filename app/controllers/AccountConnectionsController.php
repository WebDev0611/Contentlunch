<?php

class AccountConnectionsController extends BaseController {

  public function get_connections($account_id)
  {
    // Restrict user is in account
    if ( ! $this->inAccount($account_id)) {
      return $this->responseAccessDenied();
    }
    $query = AccountConnection::where('account_id', $account_id);
    if (Input::get('type')) {
      $query->where('type', Input::get('type'));
    }
    return $query->get();
  }

  public function post_connection($account_id)
  {
    // Restrict user is in account
    if ( ! $this->inAccount($account_id)) {
      return $this->responseAccessDenied();
    }
    $connection = new AccountConnection;
    $connection->account_id = $account_id;
    $connection->name = Input::get('name');
    $connection->status = Input::get('status');
    $connection->type = Input::get('type');
    $connection->provider = Input::get('provider');
    $connection->settings = Input::get('settings');
    if ($connection->save()) {
      return $this->show($connection->id);
    }
    return $this->responseError($connection->errors()->all(':message'));
  }

  public function put_connection($account_id, $connection_id)
  {
    // Restrict user is in account
    if ( ! $this->inAccount($account_id)) {
      return $this->responseAccessDenied();
    }
    $connection = AccountConnection::find($connection_id);
    $connection->name = Input::get('name');
    $connection->status = Input::get('status');
    $connection->type = Input::get('type');
    $connection->provider = Input::get('provider');
    $connection->settings = Input::get('settings');
    if ($connection->updateUniques()) {
      return $this->show($connection->id);
    }
    return $this->responseError($connection->errors()->all(':message'));
  }

  public function show($id)
  {
    return AccountConnection::find($id);
  }

  public function delete_connection($account_id, $connection_id)
  {
    // Restrict user is in account
    if ( ! $this->inAccount($account_id)) {
      return $this->responseAccessDenied();
    }
    $connection = AccountConnection::find($connection_id);
    if ($connection->delete()) {
      return array('status' => 'OK');
    }
    return $this->responseError("Unable to delete connection");
  }

}