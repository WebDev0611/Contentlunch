<?php

class ContentController extends BaseController {

  public function index()
  {
    $account = Account::find(Input::get('account_id'));
    if ( ! $account) {
      return $this->responseError("Invalid account");
    }
    if ( ! $this->inAccount($account->id)) {
      return $this->responseAccessDenied();
    }
    return Content::doQuery($account->id);
  }

  public function store()
  {
    $account = Account::find(Input::get('account_id'));
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

  public function show($id)
  {
    $content = Content::find($id);
    if ( ! $this->inAccount($content->account_id)) {
      return $this->responseAccessDenied();
    }
    return $content;
  }

  public function update($id)
  {
    $content = Content::find($id);
    if ( ! $this->inAccount($content->account_id)) {
      return $this->responseAccessDenied();
    }
    if ($content->updateUniques()) {
      return $this->show($content->id);
    }
    return $this->responseError($content->errors()->all(':message'));
  }

  public function destroy($id)
  {
    $content = Content::find($id);
    if ( ! $this->inAccount($content->account_id)) {
      return $this->responseAccessDenied();
    }
    if ($content->delete()) {
      return array('success' => 'OK');
    }
    return $this->responseError("Couldn't delete content");
  }

}
