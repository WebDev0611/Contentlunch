<?php

class UploadController extends BaseController {

  public function store($accountID) 
  {
    // Check user belongs to this account
    $account = Account::find($accountID);
    if ( ! $account || ! $this->inAccount($accountID)) {
      return $this->responseAccessDenied();
    }
    // @todo: Check user has permission to upload
    $file = Input::file('file');
    $upload = new Upload;
    // Associate upload with this account
    $upload->account()->associate($account);
    try {
      $upload->process($file);
    } catch (Exception $e) {
      Log::error($e);
      return $this->responseError($e->getMessage());
    }
    if ($upload->id) {
      return $this->show($accountID, $upload->id);
    }
    return $this->responseError("Unable to upload file");
  }

  public function show($accountID, $uploadID)
  {
    // Check user belongs to this account
    $account = Account::find($accountID);
    if ( ! $account || ! $this->inAccount($accountID)) {
      return $this->responseAccessDenied();
    }
    $upload = Upload::find($uploadID);
    return $upload;
  }

  public function destroy($accountID, $uploadID)
  {
    // Check user belongs to this account
    $account = Account::find($accountID);
    if ( ! $account || ! $this->inAccount($accountID)) {
      return $this->responseAccessDenied();
    }
    // @todo: Check user has permission to delete upload
    $upload = Upload::find($uploadID);
    // @todo: Does this delete the actual file from disk?
    if ($upload->delete()) {
      return ['success' => 'OK'];
    }
    return $this->responseError("Unable to delete file");
  }

}
