<?php namespace Launch\Notifications;

use \ContentTaskSubscriber;
use \Carbon\Carbon;
use \Queue;

class TaskNotificationHandler {

	protected $taskParentType;
	protected $subscriberClass;
	protected $subscriberTaskField;

	protected $initiator;

	protected $currentTask;

	protected $originalTask;

	protected $queueDelay = 3;

	function __construct($initiator, $taskParentType)
	{
		$this->initiator = $initiator;
		$this->taskParentType = $taskParentType;
		$this->subscriberClass = "{$taskParentType}TaskSubscriber";
		$this->subscriberTaskField = strtolower($taskParentType) . "_task_id";
	}

	public function queueNewTask(array $originalTask)
	{
		$this->originalTask = $originalTask;

		$this->subscribeUser($this->initiator->id);
		$this->subscribeUser($this->originalTask['user_id']);

		Queue::later(
            Carbon::now()->addMinutes($this->queueDelay), 
            'Launch\\Queues\\TaskNewEmailQueue', [
                'taskId' => $this->originalTask['id'],
                'taskParentType' => $this->taskParentType,
                'taskParent' => $this->getTaskParentData(),
                'initiator' => $this->initiator->toArray()
            ]
        );
	}

	public function queueUpdatedTask(array $originalTask, array $currentTask)
	{
		$this->originalTask = $originalTask;
		$this->currentTask = $currentTask;

		$dueDate = new Carbon($this->currentTask['due_date']);
        $this->currentTask['due_date'] = $dueDate->toDateString();

		if ($this->isTaskUpdated()) {

			$this->subscribeUser($this->initiator->id);
			$this->subscribeUser($this->currentTask['user_id']);
			
			if (!$this->wasTaskUpdatedRecently()) {
				
	  			$originalAssignee = \User::find($this->originalTask['user_id']);

				Queue::later(
		            Carbon::now()->addMinutes($this->queueDelay), 
		            "Launch\\Queues\\TaskUpdatedEmailQueue", [
		                'taskId' => $this->originalTask['id'],
		                'originalTask' => $this->originalTask,
		                'originalAssignee' => $originalAssignee->toArray(),
		                'taskParent' => $this->getTaskParentData(),
		                'taskParentType' => $this->taskParentType,
		                'initiator' => $this->initiator->toArray(),
		            ]
		        );
			}

		}
	}

	public function queueDeletedTask(array $originalTask)
	{
		$this->originalTask = $originalTask;

		if (!$this->wasTaskUpdatedRecently()) {

			$this->subscribeUser($this->initiator->id);

			Queue::later(
	            Carbon::now()->addMinutes($this->queueDelay), 
	            'Launch\\Queues\\TaskDeletedEmailQueue', [
	                'taskId' => $this->originalTask['id'],
	                'initiator' => $this->initiator->toArray(),
	                'taskParentType' => $this->taskParentType,
	                'taskParent' => $this->getTaskParentData()
	            ]
	        );
		}
	}

	public function getTaskParentData()
	{
		switch ($this->taskParentType) 
		{
			case "Content":
				$taskGroup = \ContentTaskGroup::whereId($this->originalTask['content_task_group_id'])->first();
				$taskParent = \Content::find($taskGroup->content_id);
				break;

			case "Campaign":
				$taskParent = \Campaign::find($this->originalTask['campaign_id']);
				break;
		}

		return $taskParent->toArray();
	}

	public function subscribeUser($userId)
	{
		$className = $this->subscriberClass;

		$className::firstOrCreate([
            'user_id' => $userId,
            "{$this->subscriberTaskField}" => $this->originalTask['id']
        ]);
	}

	public function subscribeInitiator()
	{	
		$className = $this->subscriberClass;

		$className::firstOrCreate([
            'user_id' => $this->initiator->id,
            "{$this->subscriberTaskField}" => $this->originalTask['id']
        ]);		
	}

	public function isTaskUpdated()
	{
		return ($this->originalTask['name'] != $this->currentTask['name'] ||
                $this->originalTask['user_id'] != $this->currentTask['user_id'] ||
                $this->originalTask['due_date'] != $this->currentTask['due_date'] ||
                $this->originalTask['is_complete'] != $this->currentTask['is_complete']);
	}

	public function wasTaskUpdatedRecently()
	{
		$updated_at = new Carbon($this->originalTask['updated_at']);
		return ($updated_at->diffInMinutes(Carbon::now()) < $this->queueDelay);
	}
}