<?php namespace Launch\Queues;

use Launch\Repositories\EmailRepository;
use \User;
use \CampaignTask;
use \Carbon\Carbon;

class CampaignTaskNewEmailQueue {

	public function fire($job, $data)
	{
		$email = new EmailRepository;

		$taskId = $data['taskId'];

		$task = CampaignTask::
		    whereId($data['taskId'])
		  ->with('user')
		  ->with('campaign')
		  ->first();

		if ($task && !$task->is_complete) {
			$email->sendCampaignTaskAssignment(
				$task->user->first_name,
				$task->user->email,
				$task->name,
				$task->due_date,
				$task->campaign->title,
				$task->campaign->id
			);
		}

		$job->delete();
	}
}