<?php

class LibraryController extends BaseController {
  
  /**
   * Returns a list of libraries (folders) and their uploads
   */
  public function index()
  {
    $user = Confide::user();
    if ( ! $user) {
      return $this->responseAccessDenied();
    }
    $query = Library::with('account')
      ->with('user.image')
      ->with('uploads')
      ->with('uploads.user')
      ->with('uploads.tags');
    // Limit to global library and the account the current user belongs to
    $accounts = $user->accounts;
    foreach ($accounts as $account) {
      $accountID = $account->id;
    }
    if ( ! empty($accountID)) {
      $query
        ->where('global', true)
        ->orWhereHas('account', function ($q) use ($accountID) {
          $q->where('id', $accountID);
        });
    } else {
      $query->where('global', true);
    }
    $results = $query->get();
    // Add root uploads
    $results = $results->toArray();
    $results[] = [
      'id' => 'root',
      'uploads' => []
    ];
    return $results;
  }

  /**
   * Stores a new library
   */
  public function store()
  {
    $user = Confide::user();
    if ( ! $user) {
      return $this->responseAccessDenied();
    }
    $accounts = $user->accounts;
    if ($accounts) {
      $accountID = $accounts[0]->id;
    }
    if ( ! $accountID) {
      return $this->responseAccessDenied();
    }
    // Attach library to account
    $library = new Library;
    $library->user_id = $user->id;
    $library->account_id = $accountID;
    if ($library->save()) {
      return $this->show($library->id);
    }
    return $this->responseError($library->errors()->all(':message'));
  }

  /**
   * Returns a library
   * Access: Global or user belongs to account
   */
  public function show($id)
  {
    $library = Library::with('account')
      ->with('user.image')
      ->find($id);
    // Check access
    if ( ! $library || ( ! empty($library->account->id) &&  ! $this->inAccount($library->account->id))) {
      return $this->responseAccessDenied();
    }
    return $library;
  }

  public function update($id)
  {
    $library = Library::find($id);
    if ( ! $library || ! $library->account->id || ! $this->inAccount($library->account->id)) {
      return $this->responseAccessDenied();
    }
    if ($library->updateUniques()) {
      return $this->show($id);
    }
    return $this->responseError($library->errors()->all(':message'));
  }

  public function destroy($id)
  {
    $library = Library::find($id);
    if ( ! $library || ! $library->account->id || ! $this->inAccount($library->account->id)) {
      return $this->responseAccessDenied();
    }
    if ($library->delete()) {
      return ['success' => 'OK'];
    }
    return $this->responseError("Error deleting library");
  }

}