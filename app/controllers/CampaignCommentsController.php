<?php

class CampaignCommentsController extends BaseController {
  
  public function index($accountID, $campaignID)
  {
    $guest = Session::get('guest');
    if (!$guest && !$this->inAccount($accountID)) {
      return $this->responseAccessDenied();
    }
    $campaign = Campaign::find($campaignID);
    if ( ! $campaign) {
      return $this->responseError("Campaign not found");
    }
    return $campaign->comments;
  }

  public function store($accountID, $campaignID)
  {
    $guest = Session::get('guest');
    if (!$guest && !$this->inAccount($accountID)) {
      return $this->responseAccessDenied();
    }
    $campaign = Campaign::find($campaignID);
    if ( ! $campaign) {
      return $this->responseError("Campaign not found");
    }
    $comment = new CampaignComment;
    $comment->campaign_id = $campaignID;
    if ($comment->save()) {
      return ['success' => 'OK'];
    }
    return $this->responseError($comment->errors()->all(':message'));
  }

}