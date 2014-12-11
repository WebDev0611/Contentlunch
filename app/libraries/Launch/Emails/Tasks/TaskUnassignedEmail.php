<?php namespace Launch\Emails\Tasks;

class TaskUnassignedEmail extends AbstractTaskNotificationEmail {

	public function send($subscriberId, array $originalTask, array $originalAssignee)
	{
		if ($this->initiator['id'] == $subscriberId) {
			$this->someoneHasText = $this->youHave;
			$this->viewData['someoneHasText'] = $this->youHave;
		} else {
			$this->someoneHasText = $this->heHas;
			$this->viewData['someoneHasText'] = $this->heHas;
		}

		$this->viewData['originalAssigneeFirstName'] = $originalAssignee['first_name'];
		$this->viewData['originalAssigneeLastName'] = $originalAssignee['last_name'];
		$this->viewData['originalTaskName'] = $originalTask['name'];

		if ($subscriberId == $originalAssignee['id']) {
			$this->viewData['whoIsUnassignedText'] = "you";
		} else {
			$this->viewData['whoIsUnassignedText'] = "{$originalAssignee['first_name']} {$originalAssignee['last_name']}";
		}

		if ($this->initiator['id'] == $subscriberId &&
			$subscriberId == $originalAssignee['id']) {
			$this->viewData['whoIsUnassignedText'] = "yourself";
		} elseif ($this->initiator['id'] == $originalAssignee['id'] &&
			      $subscriberId != $originalAssignee['id']) {
			$this->viewData['whoIsUnassignedText'] = "themself";
		}

		$this->mailData['template'] = 'emails.tasks.taskunassigned';
		$this->mailData['subject'] = "{$this->someoneHasText} unnasigned {$this->viewData['whoIsUnassignedText']} from '{$originalTask['name']}' on the {$this->taskParentTitle} {$this->taskParentType}";
		$this->doSend();
	}
}