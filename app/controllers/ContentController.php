<?php

class ContentController extends BaseController {

  public function index($accountID)
  {
    $account = Account::find($accountID);
    if ( ! $account) {
      return $this->responseError("Invalid account");
    }
    if ( ! $this->inAccount($account->id)) {
      return $this->responseAccessDenied();
    }
    return Content::with('campaign')
      ->with('content_type')
      ->with('account_connections')
      ->with('related')
      ->with('tags')
      ->with('user')
      ->where('account_id', $account->id)
      ->get();
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
    $content = new Content;
    $content->account_id = $accountID;
    $user = Input::get('user');
    $content->user_id = $user['id'];
    $contentType = Input::get('content_type');
    $content->content_type_id = $contentType['id'];
    $campaign = Input::get('campaign');
    $content->campaign_id = $campaign['id'];
    if ($content->save()) {
      // Attach new tags
      $tags = Input::get('tags');
      if ($tags) {
        foreach ($tags as $tag) {
          $contentTag = new ContentTag(['tag' => $tag['tag']]);
          $content->tags()->save($contentTag);
        }
      }
      // Attach account connections
      $connections = Input::get('account_connections');
      if ($connections) {
        foreach ($connections as $connection) {
          $content->account_connections()->attach($connection['id']);
        }
      }
      // Attach related content
      $related = Input::get('related');
      if ($related) {
        foreach ($related as $relatedContent) {
          $content->related()->attach($relatedContent['id']);
        }
      }
      return $this->show($accountID, $content->id);
    }
    return $this->responseError($content->errors()->all(':message'));
  }

  public function show($accountID, $id)
  {
    if ( ! $this->inAccount($accountID)) {
      return $this->responseAccessDenied();
    }
    $content = Content::with('campaign')
      ->with('content_type')
      ->with('account_connections')
      ->with('related')
      ->with('tags')
      ->with('user')
      ->find($id);
    return $content;
  }

  public function update($accountID, $id)
  {
    if ( ! $this->inAccount($accountID)) {
      return $this->responseAccessDenied();
    }
    $content = Content::find($id);
    $content->account_id = $accountID;

    // Update user from user object
    $user = Input::get('user');
    $content->user_id = $user['id'];

    // Update content type
    $contentType = Input::get('content_type');
    $content->content_type_id = $contentType['id'];

    // Update campaign
    $campaign = Input::get('campaign');
    $content->campaign_id = $campaign['id'];

    if ($content->updateUniques()) {
      
      // Sync tags
      $updateTags = Input::get('tags');
      $updateIDs = [];
      if ($updateTags) {
        foreach ($updateTags as $updateTag) {
          if (empty($updateTag['id'])) {
            // Attaching new tag to content
            $contentTag = new ContentTag(['tag' => $updateTag['tag']]);
            $content->tags()->save($contentTag);
            $updateIDs[] = $contentTag->id;
          } else {
            // Tag already exists on content
            $updateIDs[] = $updateTag['id'];
          }
        }
      }
      
      // Remove any tags that weren't present in Input
      ContentTag::where('content_id', $content->id)->whereNotIn('id', $updateIDs)->delete();

      // Sync account connections
      $connections = Input::get('account_connections');
      $connectionIDs = [];
      if ($connections) {
        foreach ($connections as $connection) {
          $connectionIDs[] = $connection['id'];
        }
      }
      $content->account_connections()->sync($connectionIDs);

      // Sync related content
      $relateds = Input::get('related');
      $relatedIDs = [];
      if ($relateds) {
        foreach ($relateds as $related) {
          $relatedIDs[] = $related['id'];
        }
      }
      $content->related()->sync($relatedIDs);

      return $this->show($accountID, $content->id);
    }
    return $this->responseError($content->errors()->all(':message'));
  }

  public function destroy($accountID, $id)
  {
    if ( ! $this->inAccount($accountID)) {
      return $this->responseAccessDenied();
    }
    $content = Content::find($id);
    if ($content->delete()) {
      return array('success' => 'OK');
    }
    return $this->responseError("Couldn't delete content");
  }

}
