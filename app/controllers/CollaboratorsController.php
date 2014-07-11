<?php

class CollaboratorsController extends BaseController {
  
  public function index($accountID, $collabType, $modelID)
  {
    if ( ! $this->inAccount($accountID)) {
      return $this->responseAccessDenied();
    }
    $model = $this->getModel($collabType, $modelID);
    if ( ! $model) {
      return $this->responseError("{$collabType} not found");
    }
    return $model->collaborators;
  }

  public function store($accountID, $collabType, $modelID, $userID = false)
  {
    if ( ! $this->inAccount($accountID)) {
      return $this->responseAccessDenied();
    }
    $model = $this->getModel($collabType, $modelID);
    if ( ! $model) {
      return $this->responseError("{$collabType} not found");
    }
    if (!$userID) {
      $userID = Input::get('user_id');
    }
    $user = User::find($userID);
    if ( ! $user) {
      return $this->responseError("User not found");
    }

    // sync collaborators
    $collabIDs = [];
    $collabs = $model->collaborators;
    foreach ($collabs as $collab) {
      $collabIDs[] = $collab->id;
    }
    if ($userID != $model->user_id) {
      $collabIDs[] = $userID;
    }
    array_unique($collabIDs);
    $model->collaborators()->sync($collabIDs);

    // Store an activity log that a collaborator is being added
    if ($collabType == 'content') {
      $currentUser = Confide::user();
      $activity = new ContentActivity([
        'user_id' => $currentUser->id,
        'content_id' => $model->id,
        'activity' => "Added ". strtoupper($user->first_name .' '. $user->last_name) ." as a collaborator"
      ]);
      $activity->save();
    }      

    return $this->index($accountID, $collabType, $modelID);
  }

  public function destroy($accountID, $collabType, $modelID, $userID)
  {
    if ( ! $this->inAccount($accountID)) {
      return $this->responseAccessDenied();
    }
    $model = $this->getModel($collabType, $modelID);
    if ( ! $model) {

    }
    $model->collaborators()->detach($userID);
    // Store an activity log that a collaborator is being removed
    if ($collabType == 'content') {
      $currentUser = Confide::user();
      $user = User::find($userID);
      $activity = new ContentActivity([
        'user_id' => $currentUser->id,
        'content_id' => $model->id,
        'activity' => "Removed ". strtoupper($user->first_name .' '. $user->last_name) ." as a collaborator"
      ]);
      $activity->save();
    }
    return $this->index($accountID, $collabType, $modelID);
  }

  public function getModel($collabType, $modelID) {
    if ($collabType == 'content') {
      return Content::find($modelID);
    } else if ($collabType == 'campaigns') {
      return Campaign::find($modelID);
    }

    return false;
  }
}