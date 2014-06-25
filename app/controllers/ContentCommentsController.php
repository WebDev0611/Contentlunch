<?php

class ContentCommentsController extends BaseController {
  
  public function index($accountID, $contentID)
  {
    if ( ! $this->inAccount($accountID)) {
      return $this->responseAccessDenied();
    }
    $content = Content::find($contentID);
    if ( ! $content) {
      return $this->responseError("Content not found");
    }
    return $content->comments;
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
    $comment = new ContentComment;
    $comment->content_id = $contentID;
    if ($comment->save()) {
      return ['success' => 'OK'];
    }
    return $this->responseError($comment->errors()->all(':message'));
  }

}