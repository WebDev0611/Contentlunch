<?php namespace Launch\Notifications;

use \Carbon\Carbon;
use \Queue;

class CampaignTaskNewNoti {

	protected $taskId;

	function __construct($taskId)
	{
		$this->taskId = $taskId;
		$this->queueEmailNotification();
	}

	public function queueEmailNotification()
	{
		Queue::later(
            Carbon::now()->addMinutes(5), 
            'Launch\\Queues\\CampaignTaskNewEmailQueue', 
            ['taskId' => $this->taskId]
        );
	}
}