<?php namespace Launch\Queues;

use Launch\Repositories\EmailRepository;
use \User;
use \ContentTask;
use \Carbon\Carbon;
use \ContentTaskGroup;
use \Content;

class ContentTaskDeletedEmailQueue {

	public function fire($job, $data)
	{
		$email = new EmailRepository;

		$email->sendContentTaskDeleted(
			$data['assigneeData']['id'],
			$data['assigneeData']['first_name'],
			$data['assigneeData']['email'],
			$data['initiatorData']['id'], 
			$data['initiatorData']['first_name'],
			$data['initiatorData']['last_name'],
			$data['initiatorData']['email'],
			$data['taskData']['name'],
			$data['taskData']['due_date'],
			$data['contentData']['title']
		);

		$job->delete();
	}
}