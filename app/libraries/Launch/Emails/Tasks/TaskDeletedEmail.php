<?php namespace Launch\Emails\Tasks;

class TaskDeletedEmail extends AbstractTaskNotificationEmail {


	public function send($subscriberId)
	{
		if ($this->initiator['id'] == $subscriberId) {
			$this->someoneHasText = $this->youHave;
			$this->viewData['someoneHasText'] = $this->youHave;
		} else {
			$this->someoneHasText = $this->heHas;
			$this->viewData['someoneHasText'] = $this->heHas;
		}

		// Don't notify yourself
		if ($this->initiator['id'] == $subscriberId) {
			return;
		}

		$this->mailData['template'] = 'emails.tasks.taskdeleted';
		$this->mailData['subject'] = "{$this->someoneHasText} deleted '{$this->currentTask['name']}' on the {$this->taskParentTitle} {$this->taskParentType}";
		$this->doSend();
	}

}