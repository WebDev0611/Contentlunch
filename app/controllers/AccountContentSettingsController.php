<?php

class AccountContentSettingsController extends BaseController {

  public function get_settings($id)
  {
    // Restrict user is in account
    if ( ! $this->inAccount($id)) {
      return $this->responseAccessDenied();
    }
    return AccountContentSettings::where('account_id', $id)->first();
  }

  public function save_settings($id)
  {
    // Restrict user is in account
    if ( ! $this->inAccount($id)) {
      return $this->responseAccessDenied();
    }
    $account = Account::find($id);
    if ( ! $account) {
      return $this->responseError("Invalid account id");
    }
    $settings = AccountContentSettings::where('account_id', $id)->first();
    if ( ! $settings) {
      $settings = new AccountContentSettings;
      $settings->account_id = $id;
    }
    $settings->include_name = Input::get('include_name');
    $settings->allow_edit_date = Input::get('allow_edit_date');
    $settings->keyword_tags = Input::get('keyword_tags');
    $settings->publishing_guidelines = Input::get('publishing_guidelines');
    $settings->persona_columns = Input::get('persona_columns');
    $settings->personas = Input::get('personas');
    if ($settings->save()) {
      return $this->get_settings($id);
    }
    return $this->responseError($settings->errors()->all(':message'));
  }

}
