<?php

namespace App\Http\Controllers;

use View;

use Auth;

class CampaignController extends Controller {

	public function index(){
		return View::make('campaign.index');
	}

}
