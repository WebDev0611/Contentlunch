<?php

use LaravelBook\Ardent\Ardent;

class Content extends Ardent {

  protected $table = 'content';

  public $autoHydrateEntityFromInput = true;

  public $forceEntityHydrationFromInput = true;

  protected $fillable = [
    'title', 'body', 'account_id', 'connection_id', 'content_type_id',
    'user_id', 'buying_stage', 'persona', 'campaign_id',
    'secondary_buying_stage', 'secondary_persona', 'status',
    'archived', 'concept'
  ];

  public static $rules = [
    'title' => 'required',
    'account_id' => 'required',
    'content_type_id' => 'required',
    'user_id' => 'required',
    'buying_stage' => 'required',
    'persona' => 'required',
    'campaign_id' => 'required'
  ];

  public function campaign()
  {
    return $this->belongsTo('Campaign');
  }

  public function collaborators()
  {
    return $this->belongsToMany('User', 'content_collaborators', 'content_id', 'user_id')->withTimestamps();
  }

  public function comments()
  {
    return $this->hasMany('ContentComment', 'content_id', 'id');
  }

  public function account_connections()
  {
    return $this->belongsToMany('AccountConnection', 'content_account_connections', 'content_id', 'account_connection_id')
      ->withTimestamps()
      ->with('connection');
  }

  public function related()
  {
    return $this->belongsToMany('Content', 'content_related', 'content_id', 'related_content_id')->withTimestamps();
  }

  public function tags()
  {
    return $this->hasMany('ContentTag', 'content_id', 'id');
  }

  public function user()
  {
    return $this->belongsTo('User');
  }

  public static function doQuery($account_id)
  {
    return DB::table('content')
      ->where('content.account_id', '=', $account_id)
      ->leftJoin('campaigns', 'content.campaign_id', '=', 'campaigns.id')
      ->leftJoin('content_account_connections', 'content.id', '=', 'content_account_connections.content_id')
      ->leftJoin('account_connections', 'content_account_connections.account_connection_id', '=', 'account_connections.id')
      ->join('users', 'users.id', '=', 'content.user_id')
      ->leftJoin('uploads', 'users.id', '=', 'uploads.user_id')
      ->get([
        'content.id',
        'content.title',
        'content.persona',
        'content.buying_stage',
        'content.campaign_id',
        'campaigns.title AS campaign_title',
        'account_connections.connection_id',
        'account_connections.name AS connection_name',
        'content.user_id',
        'users.username AS user_username',
        'uploads.filename AS user_image'
      ]);
  }

}
