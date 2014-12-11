<?php namespace Launch\Queues;

use Launch\Repositories\EmailRepository;
use Launch\Emails\Tasks\TaskAssignedEmail;
use Launch\Emails\Tasks\TaskDeletedEmail;
use Launch\Emails\Tasks\TaskCompletedEmail;
use Launch\Emails\Tasks\TaskReopenedEmail;
use Launch\Emails\Tasks\TaskUnassignedEmail;
use Launch\Emails\Tasks\TaskUpdatedEmail;
use \User;
use \ContentTask;
use \CampaignTask;
use \Carbon\Carbon;
use \ContentTaskGroup;
use \Content;

class TaskUpdatedEmailQueue {

	public function fire($job, $data)
	{
		$taskClass = "{$data['taskParentType']}Task";

		$task = $taskClass::
				withTrashed()
			  ->whereId($data['taskId'])
			  ->with('user')
			  ->first();

		$config = [
			'initiator' => $data['initiator'],
			'currentAssignee' => $task->user->toArray(),
			'currentTask' => $task->toArray(),
			'taskParentTitle' => $data['taskParent']['title'],
			'taskParentId' => $data['taskParent']['id'],
			'taskParentType' => $data['taskParentType'],
			'toEmail' => null
		];

		// $subscribers = [$task->user];
		/* TODO: Add everyone as subscriber
			- Unsubscribe assignee when they
			are explicitly removed from the
			task
			- In cases where we do or don't
			want to send to assignee, compare
			the subscriber with the task
			assignee
		*/

		$subs = $task->subscribers;

		foreach ($subs as $sub) {
			$subscribers[] = $sub->user;
		}

		if (!$isDeleted = $this->wasTaskDeleted($task)) {

			$isReassigned = $this->hasAssignedUserChanged(
				$data['originalAssignee']['id'], 
				$task->user->id
			);

		  	$isCompleted = $this->wasTaskCompleted(
		  		$data['originalTask']['is_complete'], 
		  		$task->is_complete
		  	);

		  	$isReopened = $this->isTaskReopened(
		  		$data['originalTask']['is_complete'], 
		  		$task->is_complete
		  	);


		  	$isDateOrNameUpdated = $this->isDateOrNameUpdated(
		  		$data['originalTask']['name'], 
		  		$task->name, 
		  		$data['originalTask']['due_date'], 
		  		$task->due_date
		  	);
		}

		if ($isDeleted) {

			foreach ($subscribers as $subscriber) {

				$config['toEmail'] = $subscriber->email;
				$email = new TaskDeletedEmail($config);
				$email->send($subscriber->id);
			}

			return $job->delete();
		}
		
		if ($isCompleted) {

			foreach ($subscribers as $subscriber) {

				$config['toEmail'] = $subscriber->email;
				$email = new TaskCompletedEmail($config);
				$email->send($subscriber->id);
			}

			return $job->delete();
		}

		if ($isReopened) {

			foreach ($subscribers as $subscriber) {

				$config['toEmail'] = $subscriber->email;
				$email = new TaskReopenedEmail($config);
				$email->send($subscriber->id);
			}

			return $job->delete();
		}

		if ($isReassigned) {

			// Only send assignment email to the new task user
			$config['toEmail'] = $task->user->email;
			$email = new TaskAssignedEmail($config);
			$email->send();

			// Subscribe the new user
			$this->addSubscriber(
				$data['taskParentType'],
				$data['originalTask']['id'],
				$task->user->id
			);

			// Remove the og task user from subscriber
			$this->deleteSubscriber(
				$data['taskParentType'],
				$data['originalTask']['id'],
				$data['originalAssignee']['id']
			);

			// Send to all
			// You have been unnasigned
			// Brian Small has been unnasigned
			foreach ($subscribers as $subscriber) {

				$config['toEmail'] = $subscriber->email;
				$email = new TaskUnassignedEmail($config);
				$email->send(
					$subscriber->id,
					$data['originalTask'],
					$data['originalAssignee']
				);
			}

			return $job->delete();

		}

		if (!$isReassigned && $isDateOrNameUpdated) {

			foreach ($subscribers as $subscriber) {

				$config['toEmail'] = $subscriber->email;
				$email = new TaskUpdatedEmail($config);
				$email->send(
					$subscriber->id,
					$data['originalTask']
				);
			}

			return $job->delete();

		}

		$job->delete();
		
	}

	protected function isDateOrNameUpdated(
		$originalTaskName, 
		$taskName,
		$originalTaskDueDate,
		$taskDueDate)
	{
		return ($originalTaskName != $taskName ||
			      $originalTaskDueDate != $taskDueDate);
	}

	protected function isTaskReopened($orignalIsCompleted, $taskIsCompleted)
	{
		return ($orignalIsCompleted && !$taskIsCompleted);
	}

	protected function wasTaskCompleted($orignalIsCompleted, $taskIsCompleted)
	{
		return (!$orignalIsCompleted && $taskIsCompleted);
	}

	protected function wasTaskDeleted($task)
	{
		return !$task || ($task->deleted_at != null);
	}

	protected function hasAssignedUserChanged($orignalUserId, $taskUserId)
	{
		return ($orignalUserId != $taskUserId);
	}

	protected function addSubscriber($taskParentType, $taskParentId, $userId)
	{
		$className = "{$taskParentType}TaskSubscriber";
		$type = strtolower($taskParentType) . '_task_id';

		$className::firstOrCreate([
            'user_id' => $userId,
            "$type" => $taskParentId
        ]);
	}

	protected function deleteSubscriber($taskParentType, $taskParentId, $userId)
	{
		$className = "{$taskParentType}TaskSubscriber";
		$type = strtolower($taskParentType) . '_task_id';

		$className::where("$type", $taskParentId)
			->where("user_id", $userId)
			->delete();
	}
}