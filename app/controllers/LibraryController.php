<?php

class LibraryController extends BaseController {
  
  public function index()
  {
    $query = Library::with('uploads')->with('uploads.user.image');
    if (Input::has('global')) {
      $query->where('global', Input::get('global'));
    }
    return $query->get();
  }

  public function store()
  {
    $library = Library::find(Input::get('library_id'));
    if ( ! $library) {
      return $this->responseError("Invalid library");
    }

  }

  public function storeUpload($libraryID)
  {
    $file = Input::file('file');
    $upload = new Upload;
    $user = Confide::user();
    $upload->user_id = $user->id;
    $upload->description = Input::get('description');
    try {
      $upload->process($file);
    } catch (Exception $e) {
      Log::error($e);
      return $this->responseError($e->getMessage());
    }
    if ($upload->id) {
      $upload->libraries()->sync([$libraryID]);
      //return $this->show($accountID, $upload->id);
      return ['success' => 'ok'];
    }
    return $this->responseError("Unable to upload file");
  }

}