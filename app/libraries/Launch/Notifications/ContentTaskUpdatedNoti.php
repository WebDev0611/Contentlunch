<?php namespace Launch\Notifications;

use \Carbon\Carbon;
use \Queue;
use \ContentTaskGroup; 
use \Content;
use \User;

class ContentTaskUpdatedNoti {

	protected $initiator,
			  $dbTask,
			  $inputTask;

	function __construct($initiator, $dbTask, $inputTask)
	{
		$this->initiator = $initiator;
		$this->dbTask = $dbTask;
		$this->inputTask = $inputTask;

		$inputDueDate = new Carbon($inputTask['due_date']);
        $this->inputTask['due_date'] = $inputDueDate->toDateString();

        $this->queueEmailNotification();
	}

	public function wasTaskUpdatedRecently()
	{
		$updated_at = new Carbon($this->dbTask->updated_at);
		return ($updated_at->diffInMinutes(Carbon::now()) < 1);
	}

	public function isTaskUpdated()
	{
		return ($this->dbTask->name != $this->inputTask['name'] ||
                $this->dbTask->user_id != $this->inputTask['user_id'] ||
                $this->dbTask->due_date != $this->inputTask['due_date'] ||
                $this->dbTask->is_complete != $this->inputTask['is_complete']);
	}

	public function queueEmailNotification()
	{
		if ($this->isTaskUpdated() && !$this->wasTaskUpdatedRecently()) {

			$taskGroup = ContentTaskGroup::whereId($this->dbTask->content_task_group_id)->first();

  			$content = Content::find($taskGroup->content_id);

  			$user = User::find($this->dbTask->user_id);

			Queue::later(
	            Carbon::now()->addMinutes(1), 
	            'Launch\\Queues\\ContentTaskUpdatedEmailQueue', [
	                'taskId' => $this->dbTask->id,
	                'originalTaskData' => $this->dbTask->toArray(),
	                'originalAssigneeData' => $user->toArray(),
	                'contentData' => ['title' => $content->title],
	                'initiatorData' => $this->initiator->toArray(),
	                'orignalName' => $this->dbTask->name,
	                'orignalUser' => $this->dbTask->user_id,
	                'orignalDueDate' => $this->dbTask->due_date,
	                'orignalIsCompleted' => $this->dbTask->is_complete
	            ]
	        );
		}
	}
}