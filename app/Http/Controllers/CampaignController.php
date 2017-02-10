<?php

namespace App\Http\Controllers;

use App\Account;
use App\Campaign;
use App\CampaignType;
use App\Content;
use App\Presenters\CampaignTypePresenter;
use Auth;
use Illuminate\Http\Request;
use Validator;

class CampaignController extends Controller
{
    public function index(Request $request)
    {
        $campaigns = Account::selectedAccount()->campaigns;

        return response()->json([ 'data' => $campaigns ]);
    }

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
        $data = $request->all();
        $validation = $this->createValidation($data);

        if ($validation->fails()) {
            return redirect('/campaign')->with('errors', $validation->errors());
        }

        $campaign = $this->createCampaign($data);

        $this->saveCollaborators($data, $campaign);
        $this->saveTasks($data, $campaign);

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

    protected function createCampaign(array $requestData)
    {
        return Account::selectedAccount()->campaigns()->create([
            'title' => $requestData['title'],
            'description' => $requestData['description'],
            'start_date' => $requestData['start_date'],
            'end_date' => $requestData['end_date'],
            'goals' => $requestData['goals'],
            'campaign_type_id' => (int) $requestData['type'],
            'status' => (int) $requestData['status'],
            'user_id' => Auth::id(),
        ]);
    }

    protected function saveCollaborators(array $requestData, Campaign $campaign)
    {
        $collaborators = explode(',', $requestData['collaborators']);
        $campaign->collaborators()->sync($collaborators);
    }

    protected function saveTasks(array $requestData, Campaign $campaign)
    {
        $tasks = collect(explode(',', $requestData['tasks']))
            ->filter(function($taskId) { return $taskId !== ""; });

        if (!$tasks->isEmpty()) {
            $campaign->tasks()->sync($tasks->toArray());
        }
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
        ]);

        return redirect()->route('dashboard')->with([
            'flash_message' => "Campaign updated: $campaign->title",
            'flash_message_type' => 'success',
        ]);
    }
}
