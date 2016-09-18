<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use View;

use Auth;

use App\Campaign;

use App\CampaignType;

use App\ContentType;

class CampaignController extends Controller {

	public function index(){
		$campaign_types = CampaignType::all();
		return View::make('campaign.index',
			[
				'campaign_types' => $campaign_types->toJson()
			]);
	}

	/* 
		needs validation! 
		needs error handling
	*/
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
		$campaign->user_id		= Auth::id();
		$campaign->save();

		echo json_encode($campaign);
		exit;
	}

	public function edit(Request $request, $id = null ){
		$campaign = new Campaign;
		if (is_numeric($id)) {
			$campaign = Campaign::find($id);
		}

		if($request->isMethod('post')){
			$campaign->title 		= $request->input('title');
			$campaign->description 	= $request->input('description');
			$campaign->start_date 	= $request->input('start_date');
			$campaign->end_date 	= $request->input('end_date');
			$campaign->goals 		= $request->input('goals');
			$campaign->campaign_type_id = (int)$request->input('type');
			//$campaign->budget 		= $request->input('budget');
			$campaign->status 		= (int)$request->input('status');
			//$campaign->tags 		= $request->input('tags');
			$campaign->user_id		= Auth::id();
			$campaign->save();

			echo $campaign->toJson();
			exit;
		}

		$campaign_types = CampaignType::all();
		return View::make('campaign.index',
			[
				'campaigntypedd' => CampaignType::dropdown(),
				'campaign_types' => $campaign_types->toJson(),
				'campaign' => $campaign
			]);
	}
}
