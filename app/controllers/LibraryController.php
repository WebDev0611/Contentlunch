<?php

class LibraryController extends BaseController {
  
  /**
   * Returns a list of libraries (folders) and their uploads
   * Basically, this looks up the current user and returns 
   * what they have access to... not exactly restful...
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
      ->with('uploads.tags')
      // Get count of upload views (downloads)
      ->with(['uploads.views' => function ($q) {
        $q->select( DB::raw('COUNT(*) AS total'), 'upload_views.upload_id')
          ->groupBy('upload_views.upload_id');
      }])
      // Get average upload rating
      ->with(['uploads.ratings' => function ($q) {
        $q->select( DB::raw('AVG(upload_ratings.rating) AS rating'), 'upload_ratings.upload_id')
          ->groupBy('upload_ratings.upload_id');
      }])
      // Get current user's rating
      ->with(['uploads.userRating' => function ($q) use ($user) {
        $q->where('user_id', $user->id);
      }]);
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

    // Can't figure out how to do this with one query... 
    // Get upload ids for account that are attached to root library (library_uploads.library_id == 0)
    if ( ! empty($accountID)) {
      $uploads = DB::table('uploads')
        ->join('library_uploads', function ($join) {
          $join->on('uploads.id', '=', 'library_uploads.upload_id');
          $join->where('library_uploads.library_id', '=', 0);
        })
        ->where('account_id', $accountID)
        ->get(['uploads.id']);

      $ids = [];
      if ($uploads) {
        foreach ($uploads as $upload) {
          $ids[] = $upload->id;
        }
      }

      // Duplicate all the stuff from above so folder -> uploads match
      $rootUploads = Upload::with('user')
        ->with('tags')
        ->with('user')
        ->with('libraries')
        // Get count of upload views (downloads)
        ->with(['views' => function ($q) {
          $q->select( DB::raw('COUNT(*) AS total'), 'upload_views.upload_id')
            ->groupBy('upload_views.upload_id');
        }])
        ->with('ratings')
         // Get average upload rating
        ->with(['ratings' => function ($q) {
          $q->select( DB::raw('AVG(upload_ratings.rating) AS rating'), 'upload_ratings.upload_id')
            ->groupBy('upload_ratings.upload_id');
        }])
        // Get current user's rating
        ->with(['userRating' => function ($q) use ($user) {
          $q->where('user_id', $user->id);
        }])
        ->whereIn('uploads.id', $ids)
        ->get();
      
      // Mimick a folder record
      $results[] = [
        'id' => 'root',
        'uploads' => $rootUploads->toArray()
      ];
    }
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