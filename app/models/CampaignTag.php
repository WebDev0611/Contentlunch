<?php

use LaravelBook\Ardent\Ardent;

class CampaignTag extends Ardent {

    protected $softDelete = true;

  protected $table = 'campaign_tags';

  public $autoHydrateEntityFromInput = true;

  public $forceEntityHydrationFromInput = true;

  protected $fillable = [
    'tag'
  ];

  public static $rules = [
    'tag' => 'required'
  ];

}
