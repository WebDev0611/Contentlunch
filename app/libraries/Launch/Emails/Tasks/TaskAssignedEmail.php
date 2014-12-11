<?php namespace Launch\Emails\Tasks;

class TaskAssignedEmail extends AbstractTaskNotificationEmail {

	public function send()
	{
		$this->mailData['subject'] = "You have been assigned '{$this->currentTask['name']}' on the {$this->taskParentTitle} {$this->taskParentType}";
		$this->mailData['template'] = 'emails.tasks.taskassigned';
		$this->doSend();
	}
}