<?php namespace Launch\Queues;

use Launch\Repositories\EmailRepository;
use Launch\Emails\Tasks\TaskDeletedEmail;
use \User;
use \ContentTask;
use \CampaignTask;
use \Carbon\Carbon;
use \ContentTaskGroup;
use \Content;

class TaskDeletedEmailQueue {

	public function fire($job, $data)
	{
		$taskClass = "{$data['taskParentType']}Task";

		$task = $taskClass::
				withTrashed()
			  ->whereId($data['taskId'])
			  ->with('user')
			  ->first();

		$subs = $task->subscribers;

		foreach ($subs as $sub) {
			$subscribers[] = $sub->user;
		}

		$config = [
			'initiator' => $data['initiator'],
			'currentAssignee' => $task->user->toArray(),
			'currentTask' => $task->toArray(),
			'taskParentTitle' => $data['taskParent']['title'],
			'taskParentId' => $data['taskParent']['id'],
			'taskParentType' => $data['taskParentType'],
			'toEmail' => null
		];

		foreach ($subscribers as $subscriber) {
			$config['toEmail'] = $subscriber->email;
			$email = new TaskDeletedEmail($config);
			$email->send($subscriber->id);
		}

		$job->delete();
	}
}