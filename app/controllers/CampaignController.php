<?php

class CampaignController extends BaseController {

  public function index($accountID)
  {
    $account = Account::find($accountID);
    if ( ! $account) {
      return $this->responseError("Invalid account");
    }
    if ( ! $this->inAccount($account->id)) {
      return $this->responseAccessDenied();
    }
    return Campaign::where('account_id', $account->id)
      ->with('tags')
      ->with('collaborators')
      ->get();
  }

  public function store($accountID)
  {
    $account = Account::find($accountID);
    if ( ! $account) {
      return $this->responseError("Invalid account");
    }
    if ( ! $this->inAccount($account->id)) {
      return $this->responseAccessDenied();
    }
    $campaign = new Campaign;
    if ($campaign->save()) {
      return $this->show($accountID, $campaign->id);
    }
    return $this->responseError($campaign->errors()->all(':message'));
  }

  public function show($accountID, $id)
  {
    if ( ! $this->inAccount($accountID)) {
      return $this->responseAccessDenied();
    }
    $campaign = Campaign::with('tags')
      ->with('collaborators')
      ->find($id);
    return $campaign;
  }

  public function update($accountID, $id)
  {
    if ( ! $this->inAccount($accountID)) {
      return $this->responseAccessDenied();
    }
    $campaign = Campaign::find($id);
    if ($campaign->updateUniques()) {
      return $this->show($accountID, $campaign->id);
    }
    return $this->responseError($campaign->errors()->all(':message'));
  }

  public function destroy($accountID, $id)
  {
    if ( ! $this->inAccount($accountID)) {
      return $this->responseAccessDenied();
    }
    $campaign = Campaign::find($id);
    if ($campaign->delete()) {
      return array('success' => 'OK');
    }
    return $this->responseError("Couldn't delete campaign");
  }

}
