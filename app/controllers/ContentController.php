<?php

class ContentController extends BaseController {

  public function index($accountID)
  {
    $account = Account::find($accountID);
    if ( ! $account) {
      return $this->responseError("Invalid account");
    }
    if ( ! $this->inAccount($account->id)) {
      return $this->responseAccessDenied();
    }
    return Content::with('campaign')
      ->with('collaborators')
      ->with('comments')
      ->with('content_type')
      ->with('account_connections')
      ->with('related')
      ->with('tags')
      ->with('user')
      ->where('account_id', $account->id)
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
    $content = new Content;
    if ($content->save()) {
      return $this->show($content->id);
    }
    return $this->responseError($content->errors()->all(':message'));
  }

  public function show($accountID, $id)
  {
    if ( ! $this->inAccount($accountID)) {
      return $this->responseAccessDenied();
    }
    $content = Content::with('campaign')
      ->with('collaborators')
      ->with('comments')
      ->with('content_type')
      ->with('account_connections')
      ->with('related')
      ->with('tags')
      ->with('user')
      ->find($id);
    return $content;
  }

  public function update($accountID, $id)
  {
    if ( ! $this->inAccount($accountID)) {
      return $this->responseAccessDenied();
    }
    $content = Content::find($id);
    if ($content->updateUniques()) {
      return $this->show($content->id);
    }
    return $this->responseError($content->errors()->all(':message'));
  }

  public function destroy($accountID, $id)
  {
    if ( ! $this->inAccount($accountID)) {
      return $this->responseAccessDenied();
    }
    $content = Content::find($id);
    if ($content->delete()) {
      return array('success' => 'OK');
    }
    return $this->responseError("Couldn't delete content");
  }

}
