<?php

namespace App\Http\Controllers;

use App\Account;
use App\Attachment;
use App\Campaign;
use App\CampaignType;
use App\Content;
use App\Helpers;
use App\Limit;
use App\Presenters\CampaignTypePresenter;
use App\Traits\Redirectable;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Validator;

class CampaignController extends Controller
{
    use Redirectable;

    public function index(Request $request)
    {
        $campaignCollection = Account::selectedAccount()
            ->campaigns()
            ->orderBy('created_at', 'desc')
            ->with('user')
            ->get();

        return response()->json([
            'data' => $this->addDates($campaignCollection)
        ]);
    }

    public function dashboardIndex()
    {
        $account = Account::selectedAccount();

        $activeCampaigns = $account->campaigns()->active()->orderBy('created_at', 'desc')->with('user')->get();
        $inactiveCampaigns = $account->campaigns()->inactive()->orderBy('created_at', 'desc')->with('user')->get();
        $pausedCampaigns = $account->campaigns()->paused()->orderBy('created_at', 'desc')->with('user')->get();

        return view('content.campaigns', [
            'activeCampaigns' => $this->addDates($activeCampaigns),
            'inactiveCampaigns' => $this->addDates($inactiveCampaigns),
            'pausedCampaigns' => $this->addDates($pausedCampaigns),
        ]);
    }

    protected function addDates($campaignCollection)
    {
        return $campaignCollection
            ->map(function($campaign) {
                $campaign->updated_at_diff = $campaign->present()->updatedAt;
                $campaign->started = $campaign->present()->startDateFormat('M j, Y');
                $campaign->ending = $campaign->present()->endDateFormat('M j, Y');

                return $campaign;
            });
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
            'availableContents' => $campaign->availableContents(),
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
        if (Auth::user()->cant('create', Campaign::class)) {
            return back()->with([
                'flash_message' => Limit::feedbackMessage('campaigns'),
                'flash_message_type' => 'danger',
            ]);
        }
        Auth::user()->addToLimit('campaigns');

        $request->merge([
            'start_date' => $this->formatDate($request->get('start_date')),
            'end_date' => $this->formatDate($request->get('end_date')),
        ]);

        $data = $request->all();
        $validation = $this->createValidation($data);

        if ($validation->fails()) {
            $request->flash();
            return redirect('/campaign')->with('errors', $validation->errors());
        }

        $campaign = $this->createCampaign($data);

        $this->saveCollaborators($data, $campaign);
        $this->saveTasks($data, $campaign);
        $this->handleAttachments($request->input('attachments'), $campaign);
        $this->addNewContent($data, $campaign);

        return $this->success('campaigns.index', "Campaign created: {$campaign->title}");
    }

    protected function addNewContent(array $requestData, Campaign $campaign)
    {
        if (collect($requestData)->has('newContent') && $contents = $requestData['newContent']) {
            $campaign->contents()->saveMany(Content::find($contents));
        }
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
            'campaign_type_id' => (int) $requestData['campaign_type_id'],
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

    private function formatDate($date){
        $newDateFormat = 'Y-m-d';
        return date($newDateFormat, strtotime($date));
    }

    public function update(Request $request, Campaign $campaign)
    {
        $validation = $this->createValidation($request->all());

        if ($validation->fails()) {
            $request->flash();
            return redirect('/campaign')->with('errors', $validation->errors());
        }

        $request->merge([
            'start_date' => $this->formatDate($request->get('start_date')),
            'end_date' => $this->formatDate($request->get('end_date')),
        ]);

        $campaign->update($request->all());

        $this->handleAttachments($request->input('attachments'), $campaign);
        $this->addNewContent($request->all(), $campaign);

        return $this->success('campaigns.index', "Campaign updated: $campaign->title");
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

    protected function permissionDeniedRedirect($campaign)
    {
        return $this->danger('campaigns.edit', "You don't have sufficient permissions to do this.");
    }

    /**
     * Parks a specific campaign.
     *
     * @param Campaign $campaign
     * @return \Illuminate\Http\RedirectResponse
     */
    public function park(Campaign $campaign)
    {
        if (Auth::user()->cant('edit', $campaign)) {
            return $this->permissionDeniedRedirect($campaign);
        }

        $campaign->pause();

        return $this->success('campaigns.index', 'Campaign parked.');
    }

    /**
     * Deactivates campaign.
     * @param Campaign $campaign
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deactivate(Campaign $campaign)
    {
        if (Auth::user()->cant('edit', $campaign)) {
            return $this->permissionDeniedRedirect($campaign);
        }

        $campaign->deactivate();

        return $this->success(['campaigns.edit', $campaign], 'Campaign deactivated.');
    }

    /**
     * Activates a campaign.
     * @param Campaign $campaign
     * @return \Illuminate\Http\RedirectResponse
     */
    public function activate(Campaign $campaign)
    {
        if (Auth::user()->cant('edit', $campaign)) {
           return $this->permissionDeniedRedirect($campaign);
        }

        $campaign->activate();

        return $this->success(['campaigns.edit', $campaign], 'Campaign activated.');
    }

    /**
     * Deletes a campaign.
     * @param Campaign $campaign
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Campaign $campaign)
    {
        if (Auth::user()->cant('destroy', $campaign)) {
            return $this->permissionDeniedRedirect($campaign);
        }

        $campaign->delete();

        return $this->success('campaigns.index', 'Campaign deleted.');
    }
}
