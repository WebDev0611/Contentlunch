<?php namespace Launch\Queues;

use Launch\Repositories\EmailRepository;
use \User;
use \CampaignTask;
use \Carbon\Carbon;

class CampaignTaskDeletedEmailQueue {

	public function fire($job, $data)
	{
		$email = new EmailRepository;

		$email->sendCampaignTaskDeleted(
			$data['assigneeData']['id'],
			$data['assigneeData']['first_name'],
			$data['assigneeData']['email'],
			$data['initiatorData']['id'], 
			$data['initiatorData']['first_name'],
			$data['initiatorData']['last_name'],
			$data['initiatorData']['email'],
			$data['taskData']['name'],
			$data['taskData']['due_date'],
			$data['campaignData']['title']
		);

		$job->delete();
	}
}