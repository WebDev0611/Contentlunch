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
      ->with('user')
      ->with('campaign_type')
      ->with('guest_collaborators')
      ->with('collaborators');

    if (Input::has('status')) {
      $query->where('status', Input::get('status'));
    }

    $user = Confide::User();
    if(!$this->hasAbility([], ['calendar_view_campaigns_other'])) {
      $query->where('user_id', $user->id);
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

    if(!$this->hasAbility([], ['calendar_execute_campaigns_own'])) {
      return $this->responseError('You do not have permission to create campaigns', 401);
    }

    $campaign = new Campaign;
    if ($campaign->save()) {

      if ($campaign->is_recurring) {
        $this->createRecurring($campaign);
      }
      
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
      ->with('user')
      ->with('collaborators')
      ->with('campaign_type')
      ->with('content')
      ->find($id);

    $user = Confide::User();
    if(!$this->hasAbility([], ['calendar_view_campaigns_other'])
        && $campaign->user_id != $user->id) {
      return $this->responseError('You do no have permission to view this campaign', 401);
    }

    return $campaign;
  }

  public function update($accountID, $id)
  {
    if ( ! $this->inAccount($accountID)) {
      return $this->responseAccessDenied();
    }

    $campaign = Campaign::find($id);

    $user = Confide::User();
    if(!$this->hasAbility([], ['calendar_edit_campaigns_other'])
        && $campaign->user_id != $user->id) {
      return $this->responseError('You do no have permission to edit this campaign', 401);
    }

    if ($campaign->updateUniques()) {

      if ($campaign->is_recurring) {
        if (Input::get('update_other_events')) {
          $this->updateRecurring($campaign);
        } else {
          // if we're not updating others, this item is
          // no longer part of that recurring group
          $campaign->recurring_id = null;
          $campaign->is_recurring = false;
          $campaign->save();
        }
      }

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

    $user = Confide::User();
    if(!$this->hasAbility([], ['calendar_edit_campaigns_other'])
        && $campaign->user_id != $user->id) {
      return $this->responseError('You do no have permission to delete this campaign', 401);
    }

    if ($campaign->is_recurring && Input::get('update_other_events')) {
      $this->deleteRecurring($campaign);
    }

    if ($campaign->delete()) {
      return array('success' => 'OK');
    }
    return $this->responseError("Couldn't delete campaign");
  }

  protected function createRecurring($campaign)
  {
    $campaignArray = $campaign->toArray();
    $campaigns = Campaign::where('recurring_id', $campaign->recurring_id)->get();

    // @TODO calculate when the campaign needs to repeat
    $repeats = [];
    foreach ($repeats as $repeat) {
      $camp->fill($campaignArray);
      $camp->start_date = $repeat['start_date'];
      $camp->end_date   = $repeat['end_date'];
      $camp->save();
    }
  }

  protected function updateRecurring($campaign)
  {
    $campaignArray = $campaign->toArray();
    // don't update dates
    unset($campaignArray['start_date'], $campaignArray['end_date']);

    $campaigns = Campaign::where('recurring_id', $campaign->recurring_id)->get();
    foreach ($campaign as $camp) {
      $camp->fill($campaignArray);
      $camp->save();
    }
  }

  protected function deleteRecurring($campaign)
  {
    $campaigns = Campaign::where('recurring_id', $campaign->recurring_id)->get();
    foreach ($campaign as $camp) {
      $camp->delete();
    }
  }

  public function download_csv($accountID)
  {
    $campaigns = $this->index($accountID);

    // better way to test this?
    if (get_class($campaigns) != 'Illuminate\Database\Eloquent\Collection') {
      // then $campaigns is an error
      return $campaigns;
    }

    $filename = date('Y-m-d') . ' Campaigns.csv';
    
    $data = [];
    foreach ($campaigns as $campaign) {
      $data[] = $this->flatten_array($campaign->toArray());
    }

    if (empty($data)) {
      die('No data to export.');
    }

    $firstRow = array_keys($data[0]);
    array_unshift($data, $firstRow);

    header("Content-type: text/csv");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Content-Disposition: attachment; filename = \"$filename\"");
    header("Pragma: no-cache");
    header("Expires: 0");

    $outstream = fopen("php://output", 'w');

    function _outputCSV(&$vals, $key, $filehandler) {
        fputcsv($filehandler, $vals);
    }
    array_walk($data, '_outputCSV', $outstream);

    fclose($outstream);

    // exit; // don't want the rest of the page to download, just the CSV!
  }

  protected function flatten_array($array, $masterArray = [], $prependKey = '')
  {
    foreach ($array as $key => $value) {
      if (is_array($value)) {
        // decided we didn't need the nested data
        continue;
        // $append = $this->flatten_array($value, $masterArray, "{$prependKey}{$key}_"); 
        // $masterArray = array_merge($masterArray, $append);
      } else {
        if (!preg_match('/_(?:id|at)$/', $key)) {
          $masterArray[$prependKey . $key] = $value;
        }
      }
    }

    return $masterArray;
  }
}
