<?php namespace Launch\Queues;

use Launch\Repositories\EmailRepository;
use \User;
use \CampaignTask;
use \Carbon\Carbon;

class CampaignTaskUpdatedEmailQueue {

	public function fire($job, $data)
	{
    $email = new EmailRepository;

    $id = $data['taskId'];

    $originalTaskData = $data['originalTaskData'];
    $originalAssigneeData = $data['originalAssigneeData'];
    $campaignData = $data['campaignData'];
    $initiatorData = $data['initiatorData'];

    // Pull the live task
		$task = CampaignTask::
						    whereId($data['taskId'])
						  ->with('user')
						  ->first();

		if (!$isDeleted = $this->wasTaskDeleted($task)) {
			$isDeleted = false;
			$isReassigned = $this->hasAssignedUserChanged($originalAssigneeData['id'], $task->user->id);
		  $isCompleted = $this->wasTaskCompleted($originalTaskData['is_complete'], $task->is_complete);
		  $isReopened = $this->isTaskReopened($originalTaskData['is_complete'], $task->is_complete);
		  $isDateOrNameUpdated = $this->isDateOrNameUpdated(
		  	$originalTaskData['name'], 
		  	$task->name, 
		  	$originalTaskData['due_date'], 
		  	$task->due_date
		  );
		}

		if ($isDeleted) {
			$email->sendCampaignTaskDeleted(
				$originalAssigneeData['id'],
				$originalAssigneeData['first_name'],
				$originalAssigneeData['email'],
				$initiatorData['id'], 
				$initiatorData['first_name'],
				$initiatorData['last_name'],
				$initiatorData['email'],
				$originalTaskData['name'],
				$originalTaskData['due_date'],
				$campaignData['title'],
				$campaignData['id']
			);
			return $job->delete();
		}

		// ignore: all
		// use original task data!
		// send to orignal assignee!
		if ($isCompleted) {
			$email->sendCampaignTaskComplete(
				$initiatorData['first_name'],
				$initiatorData['last_name'],
				$initiatorData['id'],
				$originalAssigneeData['id'],
				$originalAssigneeData['email'],
				$originalTaskData['name'],
				$originalTaskData['due_date'],
				$campaignData['title'],
				$campaignData['id']
			);
			return $job->delete();
		}

		// ignore: all
		// use current task data
		// send to current assignee
		if ($isReopened) {

			$email->sendCampaignTaskReopened(
				$task->user->email,
				$task->name,
				$task->due_date,
				$campaignData['title'],
				$campaignData['id']
			);

			return $job->delete();
		}

		// If new User, send the new user a generic task assignment email
		// and send orignal user email notifying that they have been
		// removed from the task
		if ($isReassigned) {

			$email->sendCampaignTaskAssignment(
				$task->user->first_name,
				$task->user->email,
				$task->name,
				$task->due_date,
				$campaignData['title'],
				$campaignData['id']
			);

			$email->sendCampaignTaskRemoval(
				$originalAssigneeData['first_name'], 
				$originalAssigneeData['email'], 
				$originalTaskData['name'], 
				$originalTaskData['due_date'],
				$campaignData['title'],
				$campaignData['id']
			);

			return $job->delete();
		}

		// Only send to orignal user. A newly assigned user
		// will get the most recent data and they won't care about the
		// change
		if (!$isReassigned && $isDateOrNameUpdated) {
			$email->sendCampaignTaskUpdated(
				$originalAssigneeData['first_name'], 
				$originalAssigneeData['email'],
				$originalTaskData['name'], 
				$originalTaskData['due_date'],
				$task->name,
				$task->due_date,
				$campaignData['title'],
				$campaignData['id']
			);
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
		return !$task;
	}

	protected function hasAssignedUserChanged($orignalUserId, $taskUserId)
	{
		return ($orignalUserId != $taskUserId);
	}
	
}