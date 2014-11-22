<?php namespace Launch\Repositories;

use \Mail;
use \Account;
use \CampaignTask;
use \ContentTask;
use \Content;
use \ContentTaskGroup;
use \User;
use \Log;
use \Carbon\Carbon;

class EmailRepository {

	public function sendCampaignTaskCreated($job, $data)
	{
		$taskId = $data['taskId'];

		$task = CampaignTask::
								whereId($taskId)
							->with('user')
							->with('campaign')
							->first();

		if ($task) {
			$account = Account::find($task->campaign->account_id);

			$mailData = array(
		    'taskName' => $task->name,
		    'dueDate' => $task->due_date,
		    'campaignTitle' => $task->campaign->title,
		    'campaignDesc' => $task->campaign->description,
		    'firstName' => $task->user->first_name,
		    'accountName' => $account->name
		  );

		  Mail::send('emails.tasks.newcampaigntask', $mailData, function ($message) use ($task) {
		    $message
		      ->to($task->user->email)
		      ->from('do-not-reply@contentlaunch.com', 'Content Launch')
		      ->subject('Campaign Task Notification');
		  });
		}

	  $job->delete();

	}

	public function runContentTaskCreated($job, $data)
	{
		$taskId = $data['taskId'];

		$task = ContentTask::
						    whereId($data['taskId'])
						  ->with('user')
						  ->first();

		if ($task) {
			$this->sendContentTaskAssignment($task);
		}

		$job->delete();
	}

	public function runContentTaskUpdated($job, $data)
	{
    $reassigned = false;

    $id = $data['taskId'];
    $orignalName = $data['orignalName'];
    $orignalUser = $data['orignalUser'];
    $orignalDueDate = $data['orignalDueDate'];
    $orignalIsCompleted = $data['orignalIsCompleted'];

		$task = ContentTask::
						    whereId($data['taskId'])
						  ->with('user')
						  ->first();

		$orignalUser = User::find($data['orignalUser']);

		if (!$task) {
			$this->sendContentTaskDeleted($orignalName, $orignalUser->first_name);
			return $job->delete();
		}

		// If new User, send the new user a generic task assignment email
		// and send orignal user email notifying that they have been
		// removed from the task
		if ($orignalUser->id != $task->user->id) {
			$this->sendContentTaskAssignment($task);
			$this->sendContentTaskRemoval($orignalUser, $task, $orignalName, $orignalDueDate);
			$reassigned = true;
		}

		// If task is completed, send an email to the orignal user
		// that it has been marked as such
		if (!$orignalIsCompleted && $task->is_complete) {
			$this->sendContentTaskComplete($task);
		}

		// If task has been re-opened, send an email to the current
		// user that it has been re-opened
		if ($orignalIsCompleted && !$task->is_complete) {
			$this->sendContentTaskReopened($task);
		}

		// If task name or due date has been changed, send an email 
		// to the current user regarding the changes
		if (!$reassigned &&
				($orignalName != $task->name ||
				$orignalDueDate != $task->due_date)) {
			$this->sendContentTaskUpdated($task, $orignalName, $orignalDueDate);
		}

		$job->delete();

	}

	public function sendContentTaskAssignment(
		$assigneeFirstName,
		$assigneeEmail,
		$taskName,
		$taskDueDate,
		$contentTitle
	){

		$mailData = array(
	    'assigneeFirstName' => $assigneeFirstName,
	    'assigneeEmail' => $assigneeEmail,
	    'taskName' => $taskName,
	    'taskDueDate' => $taskDueDate,
	    'contentTitle' => $contentTitle,
	    'date' => Carbon::now('PST')
	  );

	  Mail::send('emails.tasks.newcontenttask', $mailData, function ($message) use($mailData) {
	    $message
	      ->to($mailData['assigneeEmail'])
	      ->from('do-not-reply@contentlaunch.com', 'Content Launch')
	      ->subject("You have been assigned \"{$mailData['taskName']}\" for the {$mailData['contentTitle']} content on " . $mailData['date']->toDayDateTimeString());
	  });
	}

	public function sendContentTaskRemoval(
		$assigneeFirstName,
		$assigneeEmail,
		$taskName,
		$taskDueDate,
		$contentTitle
	){

		$mailData = array(
	    'assigneeFirstName' => $assigneeFirstName,
	    'assigneeEmail' => $assigneeEmail,
	    'taskName' => $taskName,
	    'taskDueDate' => $taskDueDate,
	    'contentTitle' => $contentTitle,
	    'date' => Carbon::now('PST')
	  );

		Mail::send('emails.tasks.contenttaskremoval', $mailData, function ($message) use($mailData) {
	    $message
	      ->to($mailData['assigneeEmail'])
	      ->from('do-not-reply@contentlaunch.com', 'Content Launch')
	      ->subject("You have been unassigned from \"{$mailData['taskName']}\" on the {$mailData['contentTitle']} content on " . $mailData['date']->toDayDateTimeString());
	  });
	}

	public function sendContentTaskDeleted(
		$assigneeId,
		$assigneeFirstName, 
		$assigneeEmail,
		$initiatorId,
		$initiatorFirstName,
		$initiatorLastName,
		$initiatorEmail,
		$taskName,
		$taskDueDate,
		$contentTitle
	){

		$youHave = 'You have';
		$heHas = "{$initiatorFirstName} {initiatorLastName} has";

		if ($initiatorId == $assigneeId) {
			$someoneHasText = $youHave;
			$someoneHas = false;
		} else {
			$someoneHasText = $heHas;
			$someoneHas = true;
		}

		$mailData = [
			'date' => Carbon::now('PST'),
			'assigneeId' => $assigneeId,
	    'assigneeFirstName' => $assigneeFirstName,
	    'assigneeEmail' => $assigneeEmail,
	    'initiatorId' => $initiatorId,
	    'initiatorFirstName' => $initiatorFirstName,
	    'initiatorLastName' => $initiatorLastName,
	    'initiatorEmail' => $initiatorEmail,
	    'taskName' => $taskName,
	    'taskDueDate' => $taskDueDate,
	    'contentTitle' => $contentTitle,
	    'someoneHas' => $someoneHas,
	    'someoneHasText' => $someoneHasText
	  ];

		Mail::send('emails.tasks.contenttaskdeleted', $mailData, function ($message) use($mailData) {
	    $message
	      ->to($mailData['assigneeEmail'])
	      ->from('do-not-reply@contentlaunch.com', 'Content Launch')
	      ->subject("{$mailData['someoneHasText']} deleted \"{$mailData['taskName']}\" on " . $mailData['date']->toDayDateTimeString());
	  });
	}

	public function sendContentTaskComplete(
		$initiatorFirstName,
		$initiatorLastName,
		$assigneeEmail,
		$taskName,
		$taskDueDate,
		$contentTitle
	){
		
		$mailData = array(
	    'initiatorFirstName' => $initiatorFirstName,
	    'initiatorLastName' => $initiatorLastName,
	    'taskName' => $taskName,
	    'taskDueDate' => $taskDueDate,
	    'contentTitle' => $contentTitle,
	    'assigneeEmail' => $assigneeEmail,
	    'date' => Carbon::now('PST')
	  );

		Mail::send('emails.tasks.contenttaskcomplete', $mailData, function ($message) use($mailData) {
	    $message
	      ->to($mailData['assigneeEmail'])
	      ->from('do-not-reply@contentlaunch.com', 'Content Launch')
	      ->subject("\"{$mailData['taskName']}\" from the {$mailData['contentTitle']} content has been marked as completed on " . $mailData['date']->toDayDateTimeString());
	  });
	}

	public function sendContentTaskReopened(
		$assigneeEmail,
		$taskName,
		$taskDueDate,
		$contentTitle
	){

		$mailData = array(
	    'assigneeEmail' => $assigneeEmail,
	    'taskName' => $taskName,
	    'taskDueDate' => $taskDueDate,
	    'contentTitle' => $contentTitle,
	    'date' => Carbon::now('PST')
	  );

		Mail::send('emails.tasks.contenttaskreopened', $mailData, function ($message) use($mailData) {
	    $message
	      ->to($mailData['assigneeEmail'])
	      ->from('do-not-reply@contentlaunch.com', 'Content Launch')
	      ->subject("\"{$mailData['taskName']}\" from {$mailData['contentTitle']} has been reopened and assigned to you on " . $mailData['date']->toDayDateTimeString());
	  });
	}

	public function sendContentTaskUpdated(
		$assigneeFirstName,
		$assigneeEmail,
		$orignalTaskName,
		$orignalTaskDueDate,
		$taskName,
		$taskDueDate,
		$contentTitle
	){
	
		$mailData = array(
			'assigneeFirstName' => $assigneeFirstName,
			'assigneeEmail' => $assigneeEmail,
	    'orignalTaskName' => $orignalTaskName,
	    'orignalTaskDueDate' => $orignalTaskDueDate,
	    'taskName' => $taskName,
	    'taskDueDate' => $taskDueDate,
	    'contentTitle' => $contentTitle,
	    'date' => Carbon::now('PST')
	  );

		Mail::send('emails.tasks.contenttaskupdated', $mailData, function ($message) use($mailData) {
	    $message
	      ->to($mailData['assigneeEmail'])
	      ->from('do-not-reply@contentlaunch.com', 'Content Launch')
	      ->subject("\"{$mailData['orignalTaskName']}\" has been updated on " . $mailData['date']->toDayDateTimeString());
	  });
	}

	public function sendBillingError($account)
	{
		$subscription = $account->accountSubscription()->orderBy('id', 'desc')->first();

	  $mailData = array(
	    'accountName' => $account->name,
	    'tier' => "Tier {$subscription->subscription_level}"
	  );

	  Mail::send('emails.account.billingerror', $mailData, function ($message) use ($account) {
	    $message
	      ->to($account->email)
	      ->from('support@contentlaunch.com', 'Launch Support')
	      ->subject('Content Launch Billing Error');
	  });
	}

	public function sendBillingInvoice($account, $amountCharged) 
	{
		$subscription = $account->accountSubscription()->orderBy('id', 'desc')->first();

		$paymentInfo = unserialize($account->payment_info);

		$mailData = array(
	    'amountCharged' => $amountCharged,
	    'cardNumber' => $paymentInfo['card_number'],
	    'dateBilled' => Carbon::now()->toDateString()
	  );

	  Mail::send('emails.account.billinginvoice', $mailData, function ($message) use ($account) {
	    $message
	      ->to($account->email)
	      ->from('support@contentlaunch.com', 'Launch Support')
	      ->subject('Content Launch Invoice');
	  });
	}

	public function sendTrialWarning($account)
	{
		$subscription = $account->accountSubscription()->orderBy('id', 'desc')->first();

	  $mailData = array(
	    'accountName' => $account->name,
	    'tier' => "Tier {$subscription->subscription_level}"
	  );

	  Mail::send('emails.account.trialnearingend', $mailData, function ($message) use ($account) {
	    $message
	      ->to($account->email)
	      ->from('support@contentlaunch.com', 'Launch Support')
	      ->subject('Content Launch Trial');
	  });
	}

	public function sendTrialHasEnded($account)
	{
		$subscription = $account->accountSubscription()->orderBy('id', 'desc')->first();

	  $mailData = array(
	    'accountName' => $account->name,
	    'tier' => "Tier {$subscription->subscription_level}"
	  );

	  Mail::send('emails.account.trialhasended', $mailData, function ($message) use ($account) {
	    $message
	      ->to($account->email)
	      ->from('support@contentlaunch.com', 'Launch Support')
	      ->subject('Content Launch Trial');
	  });
	}
}