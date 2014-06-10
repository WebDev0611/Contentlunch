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
    return $this->index($accountID, $collabType, $modelID);
  }

  public function destroy($accountID, $collabType, $modelID, $userID)
  {
    if ( ! $this->inAccount($accountID)) {
      return $this->responseAccessDenied();
    }
    $campaign = $this->getModel($collabType, $modelID);
    if ( ! $campaign) {

    }
    $campaign->collaborators()->detach($userID);
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