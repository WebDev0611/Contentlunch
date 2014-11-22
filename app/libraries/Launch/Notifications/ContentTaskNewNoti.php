<?php namespace Launch\Notifications;

use \Carbon\Carbon;
use \Queue;

class ContentTaskNewNoti {

	protected $taskId;

	function __construct($taskId)
	{
		$this->taskId = $taskId;

		$this->queueEmailNotification();
	}

	public function queueEmailNotification()
	{
		Queue::later(
            Carbon::now()->addMinutes(1), 
            'Launch\\Queues\\ContentTaskNewEmailQueue', [
                'taskId' => $this->taskId
            ]
        );	
	}
}