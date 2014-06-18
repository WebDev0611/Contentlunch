<?php

class CollaboratorsController extends BaseController {
  
  public function index($accountID, $collabType, $modelID)
  {
    if ( ! $this->inAccount($accountID)) {
      return $this->responseAccessDenied();
    }
    $campaign = $this->getModel($collabType, $modelID);
    if ( ! $campaign) {
      return $this->responseError("{$collabType} not found");
    }
    return $campaign->collaborators;
  }

  public function store($accountID, $collabType, $modelID)
  {
    if ( ! $this->inAccount($accountID)) {
      return $this->responseAccessDenied();
    }
    $model = $this->getModel($collabType, $modelID);
    if ( ! $model) {
      return $this->responseError("{$collabType} not found");
    }
    $user = User::find(Input::get('user_id'));
    if ( ! $user) {
      return $this->responseError("User not found");
    }
    $model->collaborators()->attach($user->id);
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