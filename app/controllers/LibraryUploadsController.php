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
    }
    $query = $library
      ->uploads()
      ->with('user.image');
    return $query->get();
  }

  /**
   * Stores a new upload and attaches to a library
   */
  public function store($libraryID)
  {
    $file = Input::file('file');
    $upload = new Upload;
    $user = Confide::user();
    $upload->user_id = $user->id;
    $upload->description = Input::get('description');
    $accounts = $user->accounts;
    foreach ($accounts as $account) {
      $upload->account_id = $account->id;
    }
    try {
      $upload->process($file);
    } catch (Exception $e) {
      Log::error($e);
      return $this->responseError($e->getMessage());
    }
    if ($upload->id) {
      // Attach to library
      $upload->libraries()->sync([$libraryID]);
      // Attach tags
      $tags = explode(',', Input::get('tags'));
      if ($tags) {
        foreach ($tags as $tag) {
          $uploadTag = new UploadTag(['tag' => trim($tag)]);
          $upload->tags()->save($uploadTag);
        }
      }
      return $this->show($libraryID, $upload->id);
    }
    return $this->responseError($upload->errors()->all(':message'));
  }

  public function show($libraryID, $uploadID)
  {
    $upload = Upload::with('user.image')->find($uploadID);
    return $upload;
  }

  public function update($libraryID, $uploadID)
  {
    $upload = Upload::find($uploadID);
    $upload->description = Input::get('description');
    if ($upload->update()) {
      // Attach to library
      $upload->libraries()->sync([$libraryID]);
      // Attach tags
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
    $upload = Upload::find($uploadID);
    if ($upload->delete()) {
      return ['success' => 'ok'];
    }
    return $this->responseError("Couldn't delete upload");
  }

}