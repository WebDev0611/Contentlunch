<?php

class CampaignTypeController extends BaseController {

  public function index()
  {
    return CampaignType::all();
  }

}
