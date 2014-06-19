<?php

class UploadController extends BaseController {

  public function index($accountID)
  {
    // Check user belongs to this account
    $account = Account::find($accountID);
    if ( ! $account || ! $this->inAccount($accountID)) {
      return $this->responseAccessDenied();
    }
    return Upload::where('account_id', $accountID)->get(); 
  }

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
    if (Input::has('description')) {
      $upload->description = Input::get('description');
    }
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
    $upload = Upload::with('libraries')->find($uploadID);
    return $upload;
  }

  public function update($accountID, $uploadID)
  {
    // Check user belongs to this account
    $account = Account::find($accountID);
    if ( ! $account || ! $this->inAccount($accountID)) {
      return $this->responseAccessDenied();
    }
    $upload = Upload::find($uploadID);
    if ($upload->account_id != $account->id) {
      return $this->responseAccessDenied();
    }
    if (Input::has('description')) {
      $upload->description = Input::get('description');
    }
    if (Input::has('libraries')) {
      $libraryIDs = [];
      if (Input::get('libraries')) {
        foreach (Input::get('libraries') as $library) {
          $libraryIDs[] = $library['id'];
        }
      }
      $upload->libraries()->sync($libraryIDs);
    }
    if ($upload->update()) {
      return $this->show($accountID, $uploadID);
    }
    return $this->responseError("Unable to update uploaded file");
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
    // Delete relations
    DB::table('content_uploads')->where('upload_id', $upload->id)->delete();
    // @todo: Does this delete the actual file from disk?
    if ($upload->delete()) {
      return ['success' => 'OK'];
    }
    return $this->responseError("Unable to delete file");
  }

  /**
   * Download an upload's file
   */
  public function download($uploadID)
  {
    // If upload belongs to account, make sure user has access
    // account id is null for global uploads
    $upload = Upload::find($uploadID);
    if ( ! $upload) {
      return $this->responseError("Not found");
    }
    if ($upload->account_id && ! $this->inAccount($upload->account_id)) {
      return $this->responseAccessDenied();
    }
    // Store a viewed record
    $user = Confide::user();
    $viewed = new UploadView([
      'upload_id' => $uploadID,
      'user_id' => $user->id
    ]);
    // This won't save a new record with the same upload_id,user_id
    // due to unique validation rule
    $viewed->save();
    // Return the file as a download
    $path = base_path() . $upload->path . $upload->filename;
    return Response::download($path);
  }

  /**
   * Rate an upload
   */
  public function rating($uploadID)
  {
    // If upload belongs to account, make sure user has access
    // account id is null for global uploads
    $upload = Upload::find($uploadID);
    if ( ! $upload) {
      return $this->responseError("Not found");
    }
    if ($upload->account_id && ! $this->inAccount($upload->account_id)) {
      return $this->responseAccessDenied();
    }
    $user = Confide::user();
    // If rating exists, update the rating score
    $rating = UploadRating::where('upload_id', $uploadID)
      ->where('user_id', $user->id)->first();
    if ($rating) {
      $rating->rating = Input::get('rating');
      $rating->updateUniques();
    } else {
      $rating = new UploadRating([
        'upload_id' => $uploadID,
        'user_id' => $user->id,
        'rating' => Input::get('rating')
      ]);
      $rating->save();
    }
    return $rating;
  }

}
