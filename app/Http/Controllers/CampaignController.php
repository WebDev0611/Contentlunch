<?php

namespace App\Http\Controllers;

use App\Campaign;
use App\CampaignType;
use App\Content;
use App\Presenters\CampaignTypePresenter;
use Auth;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    public function index()
    {
        $campaign_types = CampaignType::all();

        return view('campaign.index', [
            'campaign' => new Campaign(),
            'contents' => Content::take(5)->get(),
            'campaigntypedd' => CampaignTypePresenter::dropdown(),
            'campaign_types' => $campaign_types->toJson(),
        ]);
    }

    /*
        needs validation!
        needs error handling
    */
    public function create(Request $request)
    {
        $campaign = Campaign::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'goals' => $request->input('goals'),
            'campaign_type_id' => (int) $request->input('type'),
            'status' => (int) $request->input('status'),
            'user_id' => Auth::id(),
            // 'budget' => $request->input('budget'),
            // 'tags' => $request->input('tags'),
        ]);

        return response()->json($campaign);
    }

    public function edit(Request $request, $id = null)
    {
        $campaign = new Campaign();

        if (is_numeric($id)) {
            $campaign = Campaign::find($id);
        }

        if ($request->isMethod('post')) {
            $campaign->title = $request->input('title');
            $campaign->description = $request->input('description');
            $campaign->start_date = $request->input('start_date');
            $campaign->end_date = $request->input('end_date');
            $campaign->goals = $request->input('goals');
            $campaign->campaign_type_id = (int) $request->input('type');
            //$campaign->budget 		= $request->input('budget');
            $campaign->status = (int) $request->input('status');
            //$campaign->tags 		= $request->input('tags');
            $campaign->user_id = Auth::id();
            $campaign->save();

            echo $campaign->toJson();
            exit;
        }

        $campaign_types = CampaignType::all();

        return view('campaign.index', [
            'campaigntypedd' => CampaignTypePresenter::dropdown(),
            'campaign_types' => $campaign_types->toJson(),
            'campaign' => $campaign,
        ]);
    }
}
