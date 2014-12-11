<?php namespace Launch\Queues;

use Launch\Repositories\EmailRepository;
use Launch\Emails\Tasks\TaskAssignedEmail;
use \User;
use \ContentTask;
use \CampaignTask;
use \Carbon\Carbon;
use \ContentTaskGroup;
use \Content;

class TaskNewEmailQueue {

	public function fire($job, $data)
	{
		$taskClass = "{$data['taskParentType']}Task";

		$task = $taskClass::
			    whereId($data['taskId'])
			  ->with('user')
			  ->first();

		if ($task && !$task->is_complete) {

			$config = [
				'initiator' => $data['initiator'],
				'currentAssignee' => $task->user->toArray(),
				'currentTask' => $task->toArray(),
				'taskParentTitle' => $data['taskParent']['title'],
				'taskParentId' => $data['taskParent']['id'],
				'taskParentType' => $data['taskParentType'],
				'toEmail' => $task->user->email
			];

			$email = new TaskAssignedEmail($config);
			$email->send();
		}

		$job->delete();
	}
}