<?php

use LaravelBook\Ardent\Ardent;

class Campaign extends Ardent {

  protected $table = 'campaigns';

  public $autoHydrateEntityFromInput = true;

  public $forceEntityHydrationFromInput = true;

  protected $fillable = [
    'account_id', 'user_id', 'title', 'status', 'campaign_type_id',
    'start_date', 'end_date', 'is_recurring', 'description',
    'goals', 'concept', 'contact', 'partners', 'speaker_name',
    'host', 'type', 'audio_link', 'photo_needed', 'link_needed',
    'is_series', 'recurring_id', 'is_active', 'color'
  ];

  public static $rules = [
    'account_id'       => 'required',
    'title'            => 'required',
    'status'           => 'required',
    'is_active'        => 'required',
    'campaign_type_id' => 'required',
    'start_date'       => 'required',
    'end_date'         => 'required|after:start_date',
    'description'      => 'required',
  ];

  public function validate(array $rules = [], array $customMessages = []) {
    // merge any custom rules with our standard rules
    $rules = array_merge(self::$rules, $rules);
    if ($this->status == 0) {
      // don't validate these rules is it's a concept (maybe should prepend "somtimes|"?)
      unset($rules['start_date'], $rules['end_date'], $rules['campaign_type_id']);
    }
    return parent::validate($rules , $customMessages);
  }

  protected function beforeCreate()
  {
    // removed from spec 6/10/14: http://cs.mavrx.io/3DoG
    // return CampaignTaskController::createDefaultTasks($this);
  }

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

  public function content()
  {
    return $this->hasMany('Content')->with('User');
  }

  public function comments()
  {
    return $this->hasMany('CampaignComment', 'campaign_id', 'id')->with('user')->with('guest');
  }

  public function tasks()
  {
    return $this->hasMany('CampaignTask');
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

  public function user()
  {
    return $this->belongsTo('User')->with('image');
  }

}