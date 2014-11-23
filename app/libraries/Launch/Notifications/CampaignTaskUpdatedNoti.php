<?php namespace Launch\Notifications;

use \Carbon\Carbon;
use \Queue;
use \Campaign;
use \User;

class CampaignTaskUpdatedNoti {

	protected $initiator,
			  $task,
			  $originalTaskData,
			  $originalAssigneeData;

	function __construct($initiator, $task, $originalTaskData, $originalAssigneeData)
	{
		$this->initiator = $initiator;
		$this->task = $task;
		$this->originalTaskData = $originalTaskData;
		$this->originalAssigneeData = $originalAssigneeData;

		$dueDate = new Carbon($originalTaskData['due_date']);
        $this->originalTaskData['due_date'] = $dueDate->toDateString();

        $this->queueEmailNotification();
	}

	public function wasTaskUpdatedRecently()
	{
		$updated_at = new Carbon($this->originalTaskData['updated_at']);
		return ($updated_at->diffInMinutes(Carbon::now()) < 5);
	}

	public function isTaskUpdated()
	{
		return ($this->task->name != $this->originalTaskData['name'] ||
                $this->task->user_id != $this->originalTaskData['user_id'] ||
                $this->task->due_date != $this->originalTaskData['due_date'] ||
                $this->task->is_complete != $this->originalTaskData['is_complete']);
	}

	public function queueEmailNotification()
	{
		if ($this->isTaskUpdated() && !$this->wasTaskUpdatedRecently()) {

			$campaign = Campaign::find($this->task->campaign_id);

			Queue::later(
	            Carbon::now()->addMinutes(5), 
	            'Launch\\Queues\\CampaignTaskUpdatedEmailQueue', [
	            	'taskId' => $this->originalTaskData['id'],
	                'originalTaskData' => $this->originalTaskData,
	                'originalAssigneeData' => $this->originalAssigneeData,
	                'campaignData' => ['title' => $campaign->title],
	                'initiatorData' => $this->initiator->toArray()
	            ]
	        );
		}
	}
}