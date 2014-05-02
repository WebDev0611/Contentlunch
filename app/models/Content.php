<?php

use LaravelBook\Ardent\Ardent;

class Content extends Ardent {

  protected $table = 'content';

  public $autoHydrateEntityFromInput = true;

  public $forceEntityHydrationFromInput = true;

  protected $fillable = array(
    'title', 'account_id', 'connection_id', 'content_type_id',
    'user_id', 'buying_stage', 'persona', 'campaign_id',
    'secondary_buying_stage', 'secondary_persona'
  );

  public static $rules = array(
    'title' => 'required',
    'account_id' => 'required',
    'connection_id' => 'required',
    'content_type_id' => 'required',
    'user_id' => 'required',
    'buying_stage' => 'required',
    'persona' => 'required',
    'campaign_id' => 'required'
  );

  public function campaign()
  {
    return $this->belongsTo('Campaign');
  }

  public function comments()
  {
    return $this->belongsToMany('ContentComment');
  }

  public function connection()
  {
    return $this->belongsTo('AccountConnection', 'connection_id');
  }

  public function related()
  {
    return $this->belongsToMany('ContentRelated');
  }

  public function tags()
  {
    return $this->belongsToMany('ContentTag');
  }

  public function user()
  {
    return $this->belongsTo('User');
  }

  public static function doQuery($account_id)
  {
    return DB::table('content')
      ->where('content.account_id', '=', $account_id)
      ->join('campaigns', 'content.campaign_id', '=', 'campaigns.id')
      ->join('account_connections AS connections', 'content.connection_id', '=', 'connections.id')
      ->join('users', 'users.id', '=', 'content.user_id')
      ->leftJoin('uploads', 'users.id', '=', 'uploads.user_id')
      ->get(array(
        'content.id',
        'content.title',
        'content.persona',
        'content.buying_stage',
        'content.campaign_id',
        'campaigns.title AS campaign_title',
        'content.connection_id',
        'connections.provider AS connection_provider',
        'content.user_id',
        'users.username AS user_username',
        'uploads.filename AS user_image'
      ));
  }

}
