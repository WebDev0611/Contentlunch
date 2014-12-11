<?php namespace Launch\Emails\Tasks;

class TaskUpdatedEmail extends AbstractTaskNotificationEmail {

	public function send($subscriberId, $originalTask)
	{
		if ($this->initiator['id'] == $subscriberId) {
			$this->someoneHasText = $this->youHave;
			$this->viewData['someoneHasText'] = $this->youHave;
		} else {
			$this->someoneHasText = $this->heHas;
			$this->viewData['someoneHasText'] = $this->heHas;
		}

		$this->mailData['template'] = 'emails.tasks.taskupdated';
		$this->viewData['originalTaskName'] = $originalTask['name'];
		$this->viewData['originalTaskDueDate'] = $this->formattedDateString($originalTask['due_date']);
		$this->mailData['subject'] = "{$this->someoneHasText} updated the Task '{$originalTask['name']}' on the {$this->taskParentTitle} {$this->taskParentType}";
		$this->doSend();
	}
}