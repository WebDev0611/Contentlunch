<?php

use Launch\CSV;
use \Carbon\Carbon;
use \Launch\Connections\API\ConnectionConnector;

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
      ->with('collaborators.image');

    if (Input::has('status')) {
      $query->where('status', Input::get('status'));
    }

    if(Input::has('end_date')) {
      $query->where('end_date', '>=', Input::get('end_date'));
    }

      if(Input::has('start')) {
          $query->where('end_date', '>=', Input::get('start'));
      }

      if(Input::has('end')) {
          $query->where('start_date', '<=', Input::get('end'));
      }

    $user = Confide::User();
    if(!$this->hasAbility([], ['calendar_view_campaigns_other'], $accountID)) {
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

    if(!$this->hasAbility([], ['calendar_execute_campaigns_own'], $accountID)) {
      return $this->responseError('You do not have permission to create campaigns', 401);
    }

    $campaign = new Campaign;
    if ($campaign->save()) {

      // if ($campaign->is_recurring) {
      //   $this->createRecurring($campaign);
      // }
      
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
      ->with('collaborators.image')
      ->with('campaign_type')
      ->with('content')
      ->find($id);

    $user = Confide::User();
    if(!$this->hasAbility([], ['calendar_view_campaigns_other'], $accountID)
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
    if(!$this->hasAbility([], ['calendar_edit_campaigns_other'], $accountID)
        && $campaign->user_id != $user->id) {
      return $this->responseError('You do no have permission to edit this campaign', 401);
    }

    if ($campaign->updateUniques()) {

      // if ($campaign->is_recurring) {
      //   if (Input::get('update_other_events')) {
      //     $this->updateRecurring($campaign);
      //   } else {
      //     // if we're not updating others, this item is
      //     // no longer part of that recurring group
      //     $campaign->recurring_id = null;
      //     $campaign->is_recurring = false;
      //     $campaign->save();
      //   }
      // }

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

    public function updateScores($accountID, $period = '60') {
        $campaigns = Campaign::with([
            'content_scores' => function($query) use ($period) {
                    $query->where('date', '>=', Carbon::now()->subDays($period));
                    $query->with('content.account_connections');
                }
        ])->where('account_id', $accountID)
          ->get();

        $date = Carbon::now()->format('Y-m-d');

        $maxPlatforms = 10; //TODO move to config
        $maxContentPieces = 10; //TODO move to config

        foreach($campaigns as $campaign) {

            //Quantity score is based on count of unique content launched in the last $period days
            $uniqueContent = array_unique($campaign->content_scores->lists('content_id'));
            $quantityScore = 100 * count($uniqueContent) / $maxContentPieces;
            $quantityScore = min($quantityScore, 100);

            //Quality score is based on average of all content scores in the last $period days
            $totalContentScore = 0;
            $scoreCount = 0;
            foreach($campaign->content_scores as $score) {
                if($score->score !== null) {
                    $scoreCount++;
                    $totalContentScore += $score->score;
                }
            }
            if($scoreCount > 0) {
                $qualityScore = $totalContentScore / $scoreCount;
            }
            else {
                $qualityScore = null;
            }


            //Diversity score is based on number of unique platforms used in the last $period days
            $diversityCount = [];
            foreach($campaign->content_scores as $score) {
                if(count($score->content->account_connections)) {
                    $connection = $score->content->account_connections[0];
                    $api = ConnectionConnector::loadAPI($connection->connection->provider, $connection);

                    $diversityId = $api->getDiversityID();
                    $diversityCount[$diversityId] = true;
                }
            }
            $diversityCount = count($diversityCount);
            $diversityScore = 100 * $diversityCount / $maxPlatforms;


            if($qualityScore === null) {
                $totalScore = .5 * $quantityScore + .5 * $diversityScore;
            }
            else {
                $totalScore = .25 * $quantityScore + .5 * $qualityScore + .25 * $diversityScore;
            }


            $campaignScore = CampaignScore::firstOrNew(['date' => $date, 'campaign_id' => $campaign->id]);
            $campaignScore->quantity_score = $quantityScore;
            $campaignScore->quality_score = $qualityScore;
            $campaignScore->diversity_score = $diversityScore;
            $campaignScore->score = $totalScore;
            $campaignScore->save();
        }

        return ['success' => 1, 'count' => count($campaigns)];
    }

  public function destroy($accountID, $id)
  {
    if ( ! $this->inAccount($accountID)) {
      return $this->responseAccessDenied();
    }
    $campaign = Campaign::find($id);

    $user = Confide::User();
    if(!$this->hasAbility([], ['calendar_edit_campaigns_other'], $accountID)
        && $campaign->user_id != $user->id) {
      return $this->responseError('You do no have permission to delete this campaign', 401);
    }

    // if ($campaign->is_recurring && Input::get('update_other_events')) {
    //   $this->deleteRecurring($campaign);
    // }

    if ($campaign->delete()) {
      return array('success' => 'OK');
    }
    return $this->responseError("Couldn't delete campaign");
  }

  // protected function createRecurring($campaign)
  // {
  //   $campaignArray = $campaign->toArray();
  //   $campaigns = Campaign::where('recurring_id', $campaign->recurring_id)->get();

  //   // @TODO calculate when the campaign needs to repeat
  //   $repeats = [];
  //   foreach ($repeats as $repeat) {
  //     $camp->fill($campaignArray);
  //     $camp->start_date = $repeat['start_date'];
  //     $camp->end_date   = $repeat['end_date'];
  //     $camp->save();
  //   }
  // }

  // protected function updateRecurring($campaign)
  // {
  //   $campaignArray = $campaign->toArray();
  //   // don't update dates
  //   unset($campaignArray['start_date'], $campaignArray['end_date']);

  //   $campaigns = Campaign::where('recurring_id', $campaign->recurring_id)->get();
  //   foreach ($campaign as $camp) {
  //     $camp->fill($campaignArray);
  //     $camp->save();
  //   }
  // }

  // protected function deleteRecurring($campaign)
  // {
  //   $campaigns = Campaign::where('recurring_id', $campaign->recurring_id)->get();
  //   foreach ($campaign as $camp) {
  //     $camp->delete();
  //   }
  // }

  public function download_csv($accountID)
  {
    $campaigns = $this->index($accountID);

    // better way to test this?
    if (get_class($campaigns) != 'Illuminate\Database\Eloquent\Collection') {
        // then $campaigns is an error
        return $campaigns;
    }

    $filename = date('Y-m-d') . ' Campaigns.csv';
    $campaigns = CSV::flatten_collection($campaigns);

    CSV::download_csv($campaigns, $filename);
  }
}
