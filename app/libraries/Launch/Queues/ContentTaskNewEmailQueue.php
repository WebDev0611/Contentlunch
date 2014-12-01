<?php namespace Launch\Queues;

use Launch\Repositories\EmailRepository;
use \User;
use \ContentTask;
use \Carbon\Carbon;
use \ContentTaskGroup;
use \Content;

class ContentTaskNewEmailQueue {

	public function fire($job, $data)
	{
		$email = new EmailRepository;

		$taskId = $data['taskId'];

		$task = ContentTask::
		    whereId($data['taskId'])
		  ->with('user')
		  ->first();

		if ($task && !$task->is_complete) {
			$taskGroup = ContentTaskGroup::whereId($task->content_task_group_id)->first();
			$content = Content::find($taskGroup->content_id);

			$email->sendContentTaskAssignment(
				$task->user->first_name,
				$task->user->email,
				$task->name,
				$task->due_date,
				$content->title,
				$content->id
			);
		}

		$job->delete();
	}
}