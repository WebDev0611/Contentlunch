<?php

use LaravelBook\Ardent\Ardent;

class Campaign extends Ardent {

  public function tags()
  {
    return $this->belongsToMany('CampaignTag');
  }

}
