<?php

use LaravelBook\Ardent\Ardent;

class Campaign extends Ardent {

  protected $table = 'campaigns';

  public $autoHydrateEntityFromInput = true;

  public $forceEntityHydrationFromInput = true;

  protected $fillable = [
    'account_id', 'title', 'status', 'campaign_type_id',
    'start_date', 'end_date', 'is_recurring', 'description',
    'goals'
  ];

  public static $rules = [
    'account_id' => 'required',
    'title' => 'required',
    'status' => 'required',
    'campaign_type_id' => 'required',
    'start_date' => 'required',
    'end_date' => 'required',
    'description' => 'required'
  ];

  public function tags()
  {
    return $this->hasMany('CampaignTag');
  }

  public static function doQuery($account_id)
  {
    $campaigns = DB::table('campaigns')
      ->where('campaigns.account_id', '=', $account_id)
      ->join('users', 'users.id', '=', 'campaigns.user_id')
      ->join('campaign_types', 'campaigns.campaign_type_id', '=', 'campaign_types.id')
      ->leftJoin('uploads', 'users.id', '=', 'uploads.user_id')
      ->get([
        'campaigns.id',
        'campaigns.title',
        'campaigns.start_date',
        'campaigns.end_date',
        'campaigns.description',
        'campaigns.is_recurring',
        'campaigns.goals',
        'campaigns.user_id',
        'campaigns.campaign_type_id',
        'campaign_types.key AS campaign_type_key',
        'campaign_types.name AS campaign_type_name',
        'users.username AS user_username',
        'uploads.filename AS user_image'
      ]);
    if ( ! $campaigns) {
      return [];
    }
    $ids = [];
    foreach ($campaigns as $campaign) {
      $ids[] = $campaign->id;
    }
    $tags = DB::table('campaign_tags')
      ->whereIn('campaign_id', $ids)
      ->get();
    foreach ($campaigns as $campaign) {
      $campaign->campaign_tags = [];
      if ($tags) {
        foreach ($tags as $tag) {
          if ($tag->campaign_id == $campaign->id) {
            $campaign->campaign_tags[] = [
              'id' => $tag->id,
              'tag' => $tag->tag,
              'updated_at' => $tag->updated_at
            ];
          }
        }
      }
    }
    return $campaigns;
  }

}
