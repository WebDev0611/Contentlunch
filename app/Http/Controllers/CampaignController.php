<?php

namespace App\Http\Controllers;

use App\Account;
use App\Attachment;
use App\Campaign;
use App\CampaignType;
use App\Content;
use App\Helpers;
use App\Presenters\CampaignTypePresenter;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Validator;

class CampaignController extends Controller
{
    public function index(Request $request)
    {
        $campaigns = Account::selectedAccount()
            ->campaigns()
            ->with('user')
            ->get()
            ->map(function($campaign) {
                $campaign->updated_at_diff = $campaign->present()->updatedAt;
                $campaign->started = $campaign->present()->startDate;
                $campaign->ending = $campaign->present()->endDate;

                return $campaign;
            });

        return response()->json([ 'data' => $campaigns ]);
    }

    public function create(Request $request)
    {
        return $this->edit($request, new Campaign());
    }

    public function edit(Request $request, Campaign $campaign)
    {
        $data = [
            'campaign' => $campaign,
            'attachments' => $this->campaignAttachments($campaign),
            'readyToPublishContent' => $campaign->contentsReady,
            'beingWrittenContent' => $campaign->contentsWritten,
            'campaignTypesDropdown' => CampaignTypePresenter::dropdown(),
            'campaignTypes' => CampaignType::all()->toJson(),
            'isCollaborator' => $campaign->hasCollaborator(Auth::user()),
        ];

        return view('campaign.index', $data);
    }

    protected function campaignAttachments(Campaign $campaign)
    {
        return $campaign->attachments()
            ->where('filepath', '<>', '')
            ->get();
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
        $this->handleAttachments($request->input('attachments'), $campaign);

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

        $this->handleAttachments($request->input('attachments'), $campaign);

        return redirect()->route('dashboard')->with([
            'flash_message' => "Campaign updated: $campaign->title",
            'flash_message_type' => 'success',
        ]);
    }

    private function handleAttachments($files, $campaign)
    {
        $files = collect($files)->filter()->flatten()->toArray();

        foreach ($files as $fileUrl) {
            $newPath = $this->moveFileToUserFolder($fileUrl, Helpers::userFilesFolder());
            $attachment = $this->createAttachment($newPath);
            $campaign->attachments()->save($attachment);
        }
    }

    private function moveFileToUserFolder($fileUrl, $userFolder)
    {
        $fileName = substr(strstr($fileUrl, '_tmp/'), 5);
        $newPath = $userFolder.$fileName;
        $s3Path = Helpers::s3Path($fileUrl);
        Storage::move($s3Path, $newPath);

        return $newPath;
    }

    private function createAttachment($s3Path)
    {
        return Attachment::create([
            'filepath' => $s3Path,
            'filename' => Storage::url($s3Path),
            'type' => 'file',
            'extension' => Helpers::extensionFromS3Path($s3Path),
            'mime' => Storage::mimeType($s3Path),
        ]);
    }
}
