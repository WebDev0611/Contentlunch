<?php

use Launch\CSV;

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
    $query = Content::with('campaign')
      ->with('content_type')
      ->with('account_connections')
      ->with('related')
      ->with('tags')
      ->with('user')
      ->with('collaborators.image')
      ->with('guest_collaborators')
      ->where('account_id', $account->id);
    if (Input::has('campaign_id')) {
      $query->where('campaign_id', Input::get('campaign_id'));
    }
    if (Input::has('status')) {
      $query->where('status', Input::get('status'));
    }
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
    $content = new Content;
    $content->account_id = $accountID;
    $user = Input::get('user');
    $content->user_id = $user['id'];
    if (Input::has('content_type')) {
      $contentType = Input::get('content_type');
      $content->content_type_id = $contentType['id'];
    }
    if (Input::has('campaign')) {
      $campaign = Input::get('campaign');
      $content->campaign_id = $campaign['id'];
    }
    if (Input::has('upload')) {
      $upload = Input::get('upload');
      $content->upload_id = $upload['id'];
    }
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
        $relatedContents = Input::get('related');
        if ($relatedContents) {
            foreach ($relatedContents as $relatedContent) {
                $related = new ContentRelated(['content_id' => $content->id, 'related_content' => $relatedContent['related_content']]);
                $content->related()->save($related);
            }
        }

      // Attach uploads
      $uploads = Input::get('uploads');
      if ($uploads) {
        foreach ($uploads as $upload) {
          $content->uploads()->attach($upload['id']);
        }
      }
      return $this->show($accountID, $content->id);
    }
    return $this->responseError($content->errors()->all(':message'));
  }

  public function show($accountID, $id)
  {
    $hasGuestAccess = GuestCollaborator::guestCanViewContent($id);
    if (!$hasGuestAccess && !$this->inAccount($accountID)) {
      return $this->responseAccessDenied();
    }
    $content = Content::with('campaign')
      ->with('content_type')
      ->with('account_connections')
      ->with('activities.user')
      ->with('related')
      ->with('tags')
      ->with('user')
      ->with('collaborators.image')
      ->with('task_groups')
      ->with('upload')
      ->with('uploads')
      ->find($id);
    //$queries = DB::getQueryLog();
    //print_r($queries);
    return $content;
  }

  public function showActivities($accountID, $id)
  {
    $hasGuestAccess = GuestCollaborator::guestCanViewContent($id);
    if (!$hasGuestAccess && !$this->inAccount($accountID)) {
      return $this->responseAccessDenied();
    }
    return Activity::where('content_id', $id)->with('user')->get();
  }

  public function allActivities($accountID)
  {
    if (!$this->inAccount($accountID)) {
      return $this->responseAccessDenied();
    }

    $contentIDs = Content::where('account_id', $accountID)->lists('id');
    return ContentActivity::whereIn('content_id', $contentIDs)->with(['content' => function ($query) {
      $query->select(['id', 'title']);
    }])->get();
  }

  public function update($accountID, $id)
  {
    if ( ! $this->inAccount($accountID)) {
      return $this->responseAccessDenied();
    }
    $content = Content::find($id);
    $content->account_id = $accountID;

    // Update user from user object
    if (Input::has('user')) {
      $user = Input::get('user');
      $content->user_id = $user['id'];
    }

    // Update content type
    if (Input::has('content_type')) {
      $contentType = Input::get('content_type');
      $content->content_type_id = $contentType['id'];
    }

    // Update campaign
    if (Input::has('campaign')) {
      $campaign = Input::get('campaign');
      $content->campaign_id = $campaign['id'];
    }

    // Update main upload file
    if (Input::has('upload')) {
      $upload = Input::get('upload');
      $content->upload_id = $upload['id'];
    }

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
      $query = ContentTag::where('content_id', $content->id);
      if ($updateIDs) {
        $query->whereNotIn('id', $updateIDs);
      }
      $query->delete();

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
      $query = ContentRelated::where('content_id', $content->id)->delete();
      $relatedContents = Input::get('related');
      if ($relatedContents) {
        foreach ($relatedContents as $relatedContent) {
          $related = new ContentRelated(['content_id' => $content->id, 'related_content' => $relatedContent['related_content']]);
          $content->related()->save($related);
        }
      }

      // Sync uploads
      if (Input::has('uploads')) {
        $uploads = Input::get('uploads');
        $ids = [];
        foreach ($uploads as $upload) {
          $ids[] = $upload['id'];
        }
        $content->uploads()->sync($ids);
      }

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

  public function download_csv($accountID)
  {
    $content = $this->index($accountID);

    // better way to test this?
    if (get_class($content) != 'Illuminate\Database\Eloquent\Collection') {
        // then $content is an error
        return $content;
    }

    $filename = date('Y-m-d') . ' Content.csv';
    $content = CSV::flatten_collection($content);

    CSV::download_csv($content, $filename);
  }

  public function launch($accountID, $contentID, $accountConnectionID)
  {
    if ( ! $this->inAccount($accountID)) {
      return $this->responseAccessDenied();
    }
    $content = Content::with('upload')
      ->with('tags')
      ->find($contentID);
    if ($content && $content->account_id != $accountID) {
      return $this->responseAccessDenied();
    }
    if ( ! $content) {
      return $this->responseError("Content not found");
    }
    $accountConnection = AccountConnection::with('connection')
      ->with('connection')
      ->where('account_connections.account_id', $accountID)
      ->where('account_connections.id', $accountConnectionID)
      ->first();

    if ( ! $accountConnection) {
      return $this->responseError("Account Connection not found");
    }
    switch ($accountConnection->connection->provider) {
      case 'acton':
        $class = 'ActonAPI';
      break;
      case 'blogger':
        $class = 'BloggerAPI';
      break;
      case 'dropbox':
        $class = 'DropboxAPI';
      break;
      case 'facebook':
        $class = 'FacebookAPI';
      break;
      case 'google-drive':
        $class = 'GoogleDriveAPI';
      break;
      case 'google-plus':
        $class = 'GooglePlusAPI';
      break;
      case 'hubspot':
        $class = 'HubspotAPI';
      break;
      case 'linkedin':
        $class = 'LinkedInAPI';
      break;
      case 'soundcloud':
        $class = 'SoundcloudAPI';
      break;
      case 'tumblr':
        $class = 'TumblrAPI';
      break;
      case 'twitter':
        $class = 'TwitterAPI';
      break;
      case 'wordpress':
        $class = 'WordpressAPI';
      break;
      case 'vimeo':
        $class = 'VimeoAPI';
      break;
      case 'youtube':
        $class = 'YoutubeAPI';
      break;
      default:
        return $this->responseError("Connection provider: ". $accountConnection->connection->provider ." not implemented yet.");
    }
    $class = 'Launch\Connections\API\\'. $class;
    $api = new $class($accountConnection->toArray());
    if (($groupID = Input::get('group_id'))) {
      $response = $api->postToGroup($content, $groupID);
    } else {
      $response = $api->postContent($content);
    }
    if ( ! isset($response['success']) || ! isset($response['response'])) {
      echo '<pre>'; print_r($response); echo '</pre>';
      throw new \Exception("Response from connection API must set success and response");
    }
    $launch = new LaunchResponse([
      'content_id' => $content->id,
      'account_connection_id' => $accountConnection->id,
      'success' => $response['success'],
      'response' => serialize($response['response']),
    ]);
    $launch->save();
    if ( ! empty($response['error'])) {
      return $this->responseError($response['error']);
    }
    
    if(!empty($response['external_id'])) {
        DB::table('content_account_connections')
            ->where('content_id', '=', $contentID)
            ->where('account_connection_id', '=', $accountConnectionID)
            ->update(['external_id' => $response['external_id']]);
    }

    return $launch;
  }

  public function getLaunches($accountID, $contentID)
  {
    if ( ! $this->inAccount($accountID)) {
      return $this->responseAccessDenied();
    }
    $content = Content::find($contentID);
    if ( ! $content) {
      return $this->responseError('Content not found');
    }
    return LaunchResponse::where('content_id', $contentID)
      ->with('account_connection.connection')
      ->get();
  }


    public function analyze($accountID, $contentID) {
        $scribeClass = 'Launch\Connections\API\\ScribeAPI';
        $scribe = new $scribeClass();
        $content = Content::where('account_id', $accountID)->where('id', $contentID)->first();
        if (empty($content)) {
            return $this->responseError('Content not found or insufficient permissions to access content.');
        }
        // TODO: remove hardcoded domain reference
        $analysis = $scribe->contentAnalysis($content->title, $content->meta_description, $content->title, $content->body, 'http://contentlaunch.com');
        if (!$analysis['success']) {
            return $this->responseError($analysis['response']['message']);
        }
        return $analysis['response'];
    }

}
