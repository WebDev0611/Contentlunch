<?php

use LaravelBook\Ardent\Ardent;

class Campaign extends Ardent {

  protected $table = 'campaigns';

  public $autoHydrateEntityFromInput = true;

  public $forceEntityHydrationFromInput = true;

  protected $fillable = [
    'account_id', 'user_id', 'title', 'status', 'campaign_type_id',
    'start_date', 'end_date', 'is_recurring', 'description',
    'goals', 'concept'
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

  protected function beforeSave()
  {
    // Assign a new campaign color
    if (empty($this->color)) {
      $this->color = $this->getNextColor();
    }
  }

  /**
   * Gets the next campaign color to use from an ordered list
   */
  protected function getNextColor()
  {
    $colors = ['#660033', '#CC9900', '#99CCFF', '#990000', '#FFFF66', '#003366', '#CC3300', 
      '#CCFF33', '#666699', '#FF6600', '#99CC00', '#6666FF', '#FF9933', '#00CC66', '#CC0099'];
     // Rotate colors, get the last color used
    $last = DB::table('campaigns')->where('account_id', $this->account_id)->orderBy('id', 'desc')->pluck('color');
    if ( ! $last) {
      return $colors[0];
    }
    $key = array_search($last, $colors);
    // Check for last item in the array
    if ($key == (count($colors) - 1)) {
      return $colors[0];
    }
    // Return next color
    return $colors[$key + 1];
  }

  public function collaborators()
  {
    return $this->belongsToMany('User', 'campaign_collaborators', 'campaign_id', 'user_id')->withTimestamps();
  }

  public function guest_collaborators()
  {
    return $this->hasMany('GuestCollaborator', 'content_id', 'id')->where('content_type', 'campaign');
  }

  public function campaign_type()
  {
    return $this->belongsTo('CampaignType');
  }

  public function tags()
  {
    return $this->hasMany('CampaignTag');
  }

}
