<?php

use LaravelBook\Ardent\Ardent;

class CampaignComment extends Ardent {

  protected $table = 'campaign_comments';

  public $autoHydrateEntityFromInput = true;

  public $forceEntityHydrationFromInput = true;

  protected $fillable = [
    'user_id', 
    'guest_id',
    'campaign_id', 
    'comment',
  ];

  public static $rules = [
    // 'user_id'    => 'required',
    // 'guest_id'   => 'required',
    'campaign_id' => 'required',
    'comment'    => 'required',
  ];

  // public function validate(array $rules = [], array $customMessages = []) {
  //   // merge any custom rules with our standard rules
  //   $rules = array_merge(self::$rules, $rules);
  //   // only one or the other guest_id or user_id is required
  //   if ($this->user_id) {
  //     unset($rules['guest_id']);
  //   }
  //   if ($this->guest_id) {
  //     unset($rules['user_id']);
  //   }
  //   return parent::validate($rules , $customMessages);
  // }

  public function user()
  {
    return $this->belongsTo('User');
  }

  public function guest()
  {
    return $this->belongsTo('GuestCollaborator');
  }

}
