<?php

class ContentController extends BaseController {

  public function index()
  {
    $account_id = Input::get('account_id');
    $account = Account::find($account_id);
    if ( ! $account) {
      return $this->responseError("Invalid account id");
    }
    return Content::doQuery($account->id);
  }

}
