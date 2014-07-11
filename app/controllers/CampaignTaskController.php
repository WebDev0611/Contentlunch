<?php

class CampaignTaskController extends BaseController {

    public function index($accountID, $campaignID)
    {
        $account = Account::find($accountID);
        if (!$account) {
            return $this->responseError("Invalid account");
        }
        if (!$this->inAccount($account->id)) {
            return $this->responseAccessDenied();
        }

        return CampaignTask::with('campaign')
            ->with('user')
            ->where('campaign_id', $campaignID)
            ->get();
    }

    public function show($accountID, $campaignID, $taskID)
    {
        $account = Account::find($accountID);
        if (!$account) {
            return $this->responseError("Invalid account");
        }
        if (!$this->inAccount($account->id)) {
            return $this->responseAccessDenied();
        }

        return CampaignTask::with('campaign')
            ->with('user')
            ->find($taskID);
    }

    public function store($accountID, $campaignID)
    {
        $account = Account::find($accountID);
        if (!$account) {
            return $this->responseError("Invalid account");
        }
        if (!$this->inAccount($account->id)) {
            return $this->responseAccessDenied();
        }

        $task = new CampaignTask;
        $task->campaign_id = $campaignID;
        if ($task->save()) {
            // add task person to list of owners if they don't already exist
            $this->addToCampaignOwners($accountID, $campaignID, $task->user_id);
            return $this->show($accountID, $campaignID, $task->id);
        }

        return $this->responseError($task->errors()->all(':message'));
    }

    public function update($accountID, $campaignID, $taskID)
    {
        $account = Account::find($accountID);
        if (!$account) {
            return $this->responseError("Invalid account");
        }
        if (!$this->inAccount($account->id)) {
            return $this->responseAccessDenied();
        }

        $task = CampaignTask::find($taskID);
        if ($task->save()) {
            // add task person to list of owners if they don't already exist
            $this->addToCampaignOwners($accountID, $campaignID, $task->user_id);
            return $this->show($accountID, $campaignID, $task->id);
        }

        return $this->responseError($task->errors()->all(':message'));
    }

    public function destroy($accountID, $campaignID, $taskID)
    {
        $account = Account::find($accountID);
        if (!$account) {
            return $this->responseError("Invalid account");
        }
        if (!$this->inAccount($account->id)) {
            return $this->responseAccessDenied();
        }

        $task = CampaignTask::find($taskID);
        if ($task->delete()) {
            return array('success' => 'OK');
        }

        return $this->responseError("Couldn't delete campaign task");
    }

    /**
     * Create the default tasks based on a campaign's type
     * @param  Campaign $campaign The Campaign model of a newly created campaign (need id, campaign_type, user_id from it)
     * @return boolean            true on success, false otherwise
     */
    public static function createDefaultTasks($campaign)
    {
        $taskNames = @self::$defaultTasksByType[$campaign->campaign_type_id];
        if (!$taskNames) {
            echo "No task names for campaign_type: {$campaign->campaign_type_id}";
            return false;
        }

        foreach ($taskNames as $taskName) {
            $task = new CampaignTask();
            // if any Input exists, it will NOT be a campaign task in this scenario
            $task->autoHydrateEntityFromInput = false;

            $task->fill([
                'campaign_id'    => $campaign->id,
                'user_id'        => $campaign->user_id,
                'name'           => $taskName,
                'due_date'       => null,
                'date_completed' => null,
                'is_complete'    => false,
            ]);

            echo '<pre>'; print_r([
                'campaign_id'    => $campaign->id,
                'user_id'        => $campaign->user_id,
                'name'           => $taskName,
                'due_date'       => null,
                'date_completed' => null,
                'is_complete'    => false,
            ]); echo '</pre>';

            if (!$task->save()) {
                // echo '<pre>'; print_r($task->errors()->all(':message')); echo '</pre>';
                return $task->errors()->all(':message');
            }
        }

        return false;
    }

    private function addToCampaignOwners($accountID, $campaignID, $userID)
    {
        $ctrl = new CollaboratorsController();
        $ctrl->store($accountID, 'campaigns', $campaignID, $userID);
    }

    static $defaultTasksByType = [
        // Advertising Campaign
        1 => [
            'Set up campaign in SalesForce or other CRM',
            'Set up Hubspot/Act-On Assets and Workflow',
            'Build influencers list.',
            'Brief industry analysts',
            'Seed the social space with "leaks."',
            'Produce ad materials',
            'Produce emails',
            'Media Buy',
            'Get partners involved.',
            'Provide free trials, downloads, product videos, and demos.',
        ],
        
        // Branding Promotion
        2 => [
            'Build influencers list.',
            'Brief industry analysts',
            'Seed the social space with "leaks."',
            'Produce press materials',
            'Develop "unique" promotional content (creating a funny video, doing a stunt centered around an industry event, publishing a survey that supports the value of your product, or creating an interesting infographic that describes the need for your product.)',
            'Get partners involved.',
            'Provide free trials, downloads, product videos, and demos.',
        ],
        
        // Content Marketing Campaign
        3 => [
            'Determine Influencers',
            'Produce Content',
            'Get Approvals',
            'Finalize & Publish Content',
            'Promote through Social Media',
        ],
        
        // Customer Nuture
        4 => [
            'Determine personas/buyer stages',
            'Create emails',
            'Create social content',
            'Create ebooks and white papers',
            'Send emails',
            'Schedule regular social updates',
            'Set up Hubspot/Act-On Assets and Workflow',
        ],
        
        // Email Nuture Campaign
        5 => [
            'Determine personas/buyer stages',
            'Create emails',
            'Create social content',
            'Send emails',
            'Schedule regular social updates',
            'Set up Hubspot/Act-On Assets and Workflow',
        ],
        
        // Event (Non Trade Show)
        6 => [
            'Registration Page',
            'Pull List',
            'Send Invitation',
            'Promote',
            'Landing Page',
            'Follow-up Campaign',
        ],
        
        // Partner Event
        7 => [
            'Create Outline/Plan',
            'Secure Partners',
            'Get Partners Content/Ideas',
            'Assemble Promotion Plan',
            'Create Content',
            'Launch to Channels',
        ],
        
        // Podcast Series
        8 => [
            'Create Outline/Agenda',
            'Secure Presenters',
            'Develop Presentation',
            'Assemble Promotion Plan',
            'Send Invitations',
            'Create - Launch Content',
            'Practice Presentation',
            'Launch to Channels',
            'Remind Registered of Event',
            'Email Webinar Recording to Attendees',
        ],
        
        // Product Launch
        9 => [
            'Build influencers list.',
            'Brief industry analysts',
            'Seed the social space with "leaks."',
            'Produce press materials',
            'Develop "unique" promotional content (creating a funny video, doing a stunt centered around an industry event, publishing a survey that supports the value of your product, or creating an interesting infographic that describes the need for your product.)',
            'Get partners involved.',
            'Provide free trials, downloads, product videos, and demos.',
        ],
        
        // Sales Campaign
        10 => [
            'Set up campaign in SalesForce or other CRM',
            'Set up Hubspot/Act-On Assets and Workflow',
            'Build influencers list.',
            'Brief industry analysts',
            'Seed the social space with "leaks."',
            'Produce sales materials',
            'Produce sales emails',
            'Create call script',
            'Get partners involved.',
            'Provide free trials, downloads, product videos, and demos.',
        ],
        
        // Thought Leadership Series
        11 => [
            'Determine Influencers',
            'Hold Interviews',
            'Schedule Videos',
            'Produce Content',
            'Get Approvals',
            'Finalize & Publish Content',
            'Promote through Social Media',
        ],
        
        // Trade Show Event
        12 => [
            'Create Agenda',
            'Secure Presenter/Speakers/Booth Personel ',
            'Send Invitations',
            'Gather Booth Assets',
            'Gather & Produce Content',
            'Print Content (if needed)',
            'Confirm Attendance',
            'Send materials to show facility',
        ],
        
        // Webinar
        13 => [
            'Create Outline/Agenda',
            'Secure Presenters',
            'Develop Presentation',
            'Assemble Promotion Plan',
            'Send Invitations',
            'Create - Launch Content',
            'Practice Presentation',
            'Launch to Channels',
            'Remind Registered of Event',
            'Email Webinar Recording to Attendees',
        ],
    ];
}
