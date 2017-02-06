<?php

namespace App\Http\Controllers;

use App\Campaign;
use App\CampaignType;
use App\Content;
use App\Presenters\CampaignTypePresenter;
use Auth;
use Illuminate\Http\Request;
use Validator;

class CampaignController extends Controller
{
    public function create()
    {
        $data = [
            'campaign' => new Campaign(),
            'readyToPublishContent' => collect([]),
            'beingWrittenContent' => collect([]),
            'campaignTypesDropdown' => CampaignTypePresenter::dropdown(),
            'campaignTypes' => CampaignType::all()->toJson(),
        ];

        return view('campaign.index', $data);
    }


    public function edit(Request $request, Campaign $campaign)
    {
        $data = [
            'campaign' => $campaign,
            'readyToPublishContent' => collect([]),
            'beingWrittenContent' => collect([]),
            'campaignTypesDropdown' => CampaignTypePresenter::dropdown(),
            'campaignTypes' => CampaignType::all()->toJson(),
        ];

        return view('campaign.index', $data);
    }

    public function store(Request $request)
    {
        $validation = $this->createValidation($request->all());

        if ($validation->fails()) {
            return redirect('/campaign')->with('errors', $validation->errors());
        }

        $campaign = Account::selected()->campaigns()->create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'goals' => $request->input('goals'),
            'campaign_type_id' => (int) $request->input('type'),
            'status' => (int) $request->input('status'),
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('dashboard')->with([
            'flash_message' => "Campaign created: $campaign->title",
            'flash_message_type' => 'success',
        ]);
    }

    protected function createValidation(array $requestData)
    {
        return Validator::make($requestData, [
            'title' => 'required',
            'status' => 'required',
        ]);
    }

    public function update(Request $request, Campaign $campaign)
    {
        $validation = $this->createValidation($request->all());

        if ($validation->fails()) {
            return redirect('/campaign')->with('errors', $validation->errors());
        }

        $campaign->update([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'goals' => $request->input('goals'),
            'campaign_type_id' => (int) $request->input('type'),
            'status' => (int) $request->input('status'),
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('dashboard')->with([
            'flash_message' => "Campaign updated: $campaign->title",
            'flash_message_type' => 'success',
        ]);
    }
}
