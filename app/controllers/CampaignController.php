<?php

class CampaignController extends BaseController {

  public function index($accountID)
  {
    $account = Account::find($accountID);
    if ( ! $account) {
      return $this->responseError("Invalid account");
    }
    if ( ! $this->inAccount($account->id)) {
      return $this->responseAccessDenied();
    }
    $query = Campaign::where('account_id', $account->id)
      ->with('tags')
      ->with('guest_collaborators')
      ->with('collaborators');

    // uncomment when we're ready to filter by status
    // if (Input::has('status')) {
    //   $query->where('status', Input::get('status'));
    // }

    return $query->get();
  }

  public function store($accountID)
  {
    $account = Account::find($accountID);
    if ( ! $account) {
      return $this->responseError("Invalid account");
    }
    if ( ! $this->inAccount($account->id)) {
      return $this->responseAccessDenied();
    }
    $campaign = new Campaign;
    if ($campaign->save()) {
      
      // Attach new tags
      $tags = Input::get('tags');
      if ($tags) {
        foreach ($tags as $tag) {
          $campaignTag = new CampaignTag(['tag' => $tag['tag']]);
          $campaign->tags()->save($campaignTag);
        }
      }

      // Attach collaborators
      $collabs = Input::get('collaborators');
      if ($collabs) {
        foreach ($collabs as $collab) {
          $campaign->collaborators()->attach($collab['id']);
        }
      }

      return $this->show($accountID, $campaign->id);
    }
    return $this->responseError($campaign->errors()->all(':message'));
  }

  public function show($accountID, $id)
  {
    if ( ! $this->inAccount($accountID)) {
      return $this->responseAccessDenied();
    }
    $campaign = Campaign::with('tags')
      ->with('collaborators')
      ->with('campaign_type')
      ->find($id);
    return $campaign;
  }

  public function update($accountID, $id)
  {
    if ( ! $this->inAccount($accountID)) {
      return $this->responseAccessDenied();
    }
    $campaign = Campaign::find($id);
    if ($campaign->updateUniques()) {

      // Sync tags
      $updateTags = Input::get('tags');
      $updateIDs = [];
      if ($updateTags) {
        foreach ($updateTags as $updateTag) {
          if (empty($updateTag['id'])) {
            // Attaching new tag to content
            $campaignTag = new CampaignTag(['tag' => $updateTag['tag']]);
            $campaign->tags()->save($campaignTag);
            $updateIDs[] = $campaignTag->id;
          } else {
            // Tag already exists on content
            $updateIDs[] = $updateTag['id'];
          }
        }
      }

      // Remove any tags that weren't present in Input
      $query = CampaignTag::where('campaign_id', $campaign->id);
      if ($updateIDs) {
        $query->whereNotIn('id', $updateIDs);
      }
      $query->delete();

      // Sync collaborators
      $collabIDs = [];
      $collabs = Input::get('collaborators');
      if ($collabs) {
        foreach ($collabs as $collab) {
          $collabIDs[] = $collab['id'];
        }
      }
      $campaign->collaborators()->sync($collabIDs);

      return $this->show($accountID, $campaign->id);
    }
    return $this->responseError($campaign->errors()->all(':message'));
  }

  public function destroy($accountID, $id)
  {
    if ( ! $this->inAccount($accountID)) {
      return $this->responseAccessDenied();
    }
    $campaign = Campaign::find($id);
    if ($campaign->delete()) {
      return array('success' => 'OK');
    }
    return $this->responseError("Couldn't delete campaign");
  }

}
