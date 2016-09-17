<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use View;

use Auth;

use App\Campaign;

use App\CampaignType;

class CampaignController extends Controller {

	public function index(){
		$campaign_types = CampaignType::all();
		return View::make('campaign.index',
			[
				'campaign_types' => $campaign_types->toJson()
			]);
	}


	public function create(Request $request){

		$campaign = new Campaign;

		$campaign->title 		= $request->input('title');
		$campaign->description 	= $request->input('description');
		$campaign->start_date 	= $request->input('start_date');
		$campaign->end_date 	= $request->input('end_date');
		$campaign->goals 		= $request->input('goals');
		$campaign->campaign_type_id = (int)$request->input('type');
		//$campaign->budget 		= $request->input('budget');
		$campaign->status 		= (int)$request->input('status');
		//$campaign->tags 		= $request->input('tags');

		$campaign->save();

		echo json_encode($campaign);
		exit;
	}
}
