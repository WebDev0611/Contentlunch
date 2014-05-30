<?php

class CollaboratorsController extends BaseController {
  
  public function index($accountID, $contentID)
  {
    if ( ! $this->inAccount($accountID)) {
      return $this->responseAccessDenied();
    }
    $content = Content::find($contentID);
    if ( ! $content) {
      return $this->responseError("Content not found");
    }
    return $content->collaborators;
  }

  public function store($accountID, $contentID)
  {
    if ( ! $this->inAccount($accountID)) {
      return $this->responseAccessDenied();
    }
    $content = Content::find($contentID);
    if ( ! $content) {
      return $this->responseError("Content not found");
    }
    $user = User::find(Input::get('user_id'));
    if ( ! $user) {
      return $this->responseError("User not found");
    }
    $content->collaborators()->attach($user->id);
    return $this->index($accountID, $contentID);
  }

  public function destroy($accountID, $contentID, $userID)
  {
    if ( ! $this->inAccount($accountID)) {
      return $this->responseAccessDenied();
    }
    $content = Content::find($contentID);
    if ( ! $content) {
      return $this->responseError("Content not found");
    }
    $content->collaborators()->detach($userID);
    return $this->index($accountID, $contentID);
  }

}