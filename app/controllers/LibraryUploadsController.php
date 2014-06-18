<?php

class LibraryUploadsController extends BaseController {
  
  /**
   * Returns a list of uploads for a library
   */
  public function index($libraryID)
  {
    if ($libraryID == 'global') {
      $library = Library::where('global', true)->first();
    } else {
      $library = Library::find($libraryID);
      // Does user belong to account?
      if ( ! $library || ! $library->account || ! $this->inAccount($library->account->id)) {
        return $this->responseAccessDenied();
      }
      $library = Library::find($libraryID);
    }
    $query = $library
      ->uploads()
      ->with('tags')
      ->with('user.image');
    return $query->get();
  }

  /**
   * Stores a new upload and attaches to a library
   */
  public function store($libraryID)
  {
    $user = Confide::user();
    $accounts = $user->accounts;
    $accountID = null;
    if ($accounts) {
      foreach ($accounts as $account) {
        $accountID = $account->id;
      }
    }
    if ($libraryID != 'root') {
      echo $libraryID;
      dd('here');
      $library = Library::find($libraryID);
      // Does user belong to account?
      if ( ! $library || ! $library->account || ! $this->inAccount($library->account->id)) {
        return $this->responseAccessDenied();
      }
    }
    $file = Input::file('file');
    if (empty($file)) {
      return $this->responseError("File is required");
    }
    $upload = new Upload;
    $upload->user_id = $user->id;
    $upload->description = Input::get('description');
    $upload->account_id = $accountID;
    try {
      $upload->process($file);
    } catch (Exception $e) {
      Log::error($e);
      return $this->responseError($e->getMessage());
    }
    if ($upload->id) {
      // If uploading to root library, store a record in library_uploads
      // with library_id of 0
      if ($libraryID == 'root') {
        DB::table('library_uploads')->insert([
          'upload_id' => $upload->id,
          'library_id' => 0
        ]);
      } else {
        // Attach to library
        $upload->libraries()->sync([$libraryID]);
      }
      
      // Attach tags
      $tags = explode(',', Input::get('tags'));
      if ($tags) {
        foreach ($tags as $tag) {
          $uploadTag = new UploadTag(['tag' => trim($tag)]);
          $upload->tags()->save($uploadTag);
        }
      }
      if ($libraryID == 'root') {
        return ['success' => 'OK'];
      }
      return $this->show($libraryID, $upload->id);
    }
    return $this->responseError($upload->errors()->all(':message'));
  }

  public function show($libraryID, $uploadID)
  {
    $library = Library::find($libraryID);
    // Does user belong to account?
    if ( ! $library || ! $library->account || ! $this->inAccount($library->account->id)) {
      return $this->responseAccessDenied();
    }
    $upload = Upload::with('user.image')->find($uploadID);
    return $upload;
  }

  public function update($libraryID, $uploadID)
  {
    $library = Library::find($libraryID);
    // Does user belong to account?
    if ( ! $library || ! $library->account || ! $this->inAccount($library->account->id)) {
      return $this->responseAccessDenied();
    }
    $upload = Upload::find($uploadID);
    $upload->description = Input::get('description');
    if ($upload->update()) {
      // Attach to library
      if ($libraryID != 'root') {
        $upload->libraries()->sync([$libraryID]);
      } else {
        $upload->libraries()->sync([]);
      }

      // Attach tags
      if ($upload->tags) {
        foreach ($upload->tags as $tag) {
          $tag->delete();
        }
      }
      $tags = explode(',', Input::get('tags'));
      if ($tags) {
        foreach ($tags as $tag) {
          $uploadTag = new UploadTag(['tag' => trim($tag)]);
          $upload->tags()->save($uploadTag);
        }
      }
      return $this->show($libraryID, $uploadID);
    }
    return $this->responseError($upload->errors()->all(':message'));
  }

  public function destroy($libraryID, $uploadID)
  {
    $library = Library::find($libraryID);
    // Does user belong to account?
    if ( ! $library || ! $library->account || ! $this->inAccount($library->account->id)) {
      return $this->responseAccessDenied();
    }
    $upload = Upload::find($uploadID);
    if ($upload->delete()) {
      return ['success' => 'ok'];
    }
    return $this->responseError("Couldn't delete upload");
  }

}