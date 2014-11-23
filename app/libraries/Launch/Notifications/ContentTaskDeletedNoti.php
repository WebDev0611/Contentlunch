<?php namespace Launch\Notifications;

use \Carbon\Carbon;
use \Queue;
use \ContentTaskGroup; 
use \Content;
use \User;

class ContentTaskDeletedNoti {

	protected $task,
			  $initiator,
			  $assignee;

	function __construct($initiator, $task)
	{
		$this->task = $task;
		$this->initiator = $initiator;
		$this->assignee = User::find($this->task->user_id);

		$this->queueEmailNotification();
	}

	public function wasTaskUpdatedRecently()
	{
		$updated_at = new Carbon($this->task->updated_at);
		return ($updated_at->diffInMinutes(Carbon::now()) < 5);
	}

	public function queueEmailNotification()
	{
		if (!$this->wasTaskUpdatedRecently()) {

			$taskGroup = ContentTaskGroup::whereId($this->task->content_task_group_id)->first();

  			$content = Content::find($taskGroup->content_id);

			Queue::later(
	            Carbon::now()->addMinutes(5), 
	            'Launch\\Queues\\ContentTaskDeletedEmailQueue', [
	                'taskData' => $this->task->toArray(),
	                'initiatorData' => $this->initiator->toArray(),
	                'assigneeData' => $this->assignee->toArray(),
	                'contentData' => ['title' => $content->title]
	            ]
	        );
		}	
	}
}