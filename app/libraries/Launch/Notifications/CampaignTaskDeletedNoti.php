<?php namespace Launch\Notifications;

use \Carbon\Carbon;
use \Queue;
use \Campaign;

class CampaignTaskDeletedNoti {

	protected $task,
			  $initiator,
			  $assignee;

	function __construct($initiator, $task, $assignee)
	{
		$this->task = $task;
		$this->initiator = $initiator;
		$this->assignee = $assignee;

		$this->queueEmailNotification();
	}

	public function wasTaskUpdatedRecently()
	{
		$updated_at = new Carbon($this->task['updated_at']);
		return ($updated_at->diffInMinutes(Carbon::now()) < 5);
	}

	public function queueEmailNotification()
	{
		if (!$this->wasTaskUpdatedRecently()) {

  			$campaign = Campaign::find($this->task['campaign_id']);

			Queue::later(
	            Carbon::now()->addMinutes(5), 
	            'Launch\\Queues\\CampaignTaskDeletedEmailQueue', [
	                'taskData' => $this->task,
	                'initiatorData' => $this->initiator,
	                'assigneeData' => $this->assignee,
	                'campaignData' => ['title' => $campaign->title, 'id' => $campaign->id]
	            ]
	        );
		}	
	}
}