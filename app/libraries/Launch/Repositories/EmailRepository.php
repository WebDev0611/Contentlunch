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


	public function sendContentTaskReminder(
		$assigneeEmail,
		$assigneeFirstName,
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

		Mail::send('emails.tasks.contenttaskreminder', $mailData, function ($message) use($mailData) {
	    $message
	      ->to($mailData['assigneeEmail'])
	      ->from('do-not-reply@contentlaunch.com', 'Content Launch')
	      ->subject("Reminder that \"{$mailData['taskName']}\" on the {$mailData['contentTitle']} content is due tomorrow.");
	  });
	}

	public function sendCampaignTaskReminder(
		$assigneeEmail,
		$assigneeFirstName,
		$taskName,
		$taskDueDate,
		$campaignTitle
	){

		$mailData = array(
	    'assigneeFirstName' => $assigneeFirstName,
	    'assigneeEmail' => $assigneeEmail,
	    'taskName' => $taskName,
	    'taskDueDate' => $taskDueDate,
	    'campaignTitle' => $campaignTitle,
	    'date' => Carbon::now('PST')
	  );

		Mail::send('emails.tasks.campaigntaskreminder', $mailData, function ($message) use($mailData) {
	    $message
	      ->to($mailData['assigneeEmail'])
	      ->from('do-not-reply@contentlaunch.com', 'Content Launch')
	      ->subject("Reminder that \"{$mailData['taskName']}\" on the {$mailData['campaignTitle']} campaign is due tomorrow.");
	  });	  
	}

	public function sendCampaignTaskAssignment(
		$assigneeFirstName,
		$assigneeEmail,
		$taskName,
		$taskDueDate,
		$campaignTitle
	){

		$mailData = array(
	    'assigneeFirstName' => $assigneeFirstName,
	    'assigneeEmail' => $assigneeEmail,
	    'taskName' => $taskName,
	    'taskDueDate' => $taskDueDate,
	    'campaignTitle' => $campaignTitle,
	    'date' => Carbon::now('PST')
	  );

		Mail::send('emails.tasks.newcampaigntask', $mailData, function ($message) use($mailData) {
	    $message
	      ->to($mailData['assigneeEmail'])
	      ->from('do-not-reply@contentlaunch.com', 'Content Launch')
	      ->subject("You have been assigned \"{$mailData['taskName']}\" on the {$mailData['campaignTitle']} campaign on " . $mailData['date']->toDayDateTimeString());
	  });
	}

	public function sendCampaignTaskRemoval(
		$assigneeFirstName,
		$assigneeEmail,
		$taskName,
		$taskDueDate,
		$campaignTitle
	){

		$mailData = array(
	    'assigneeFirstName' => $assigneeFirstName,
	    'assigneeEmail' => $assigneeEmail,
	    'taskName' => $taskName,
	    'taskDueDate' => $taskDueDate,
	    'campaignTitle' => $campaignTitle,
	    'date' => Carbon::now('PST')
	  );

		Mail::send('emails.tasks.campaigntaskremoval', $mailData, function ($message) use($mailData) {
	    $message
	      ->to($mailData['assigneeEmail'])
	      ->from('do-not-reply@contentlaunch.com', 'Content Launch')
	      ->subject("You have been unassigned from \"{$mailData['taskName']}\" on the {$mailData['campaignTitle']} campaign on " . $mailData['date']->toDayDateTimeString());
	  });
	}

	public function sendCampaignTaskDeleted(
		$assigneeId,
		$assigneeFirstName, 
		$assigneeEmail,
		$initiatorId,
		$initiatorFirstName,
		$initiatorLastName,
		$initiatorEmail,
		$taskName,
		$taskDueDate,
		$campaignTitle
	){

		$youHave = 'You have';
		$heHas = "{$initiatorFirstName} {$initiatorLastName} has";

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
	    'campaignTitle' => $campaignTitle,
	    'someoneHas' => $someoneHas,
	    'someoneHasText' => $someoneHasText
	  ];

		Mail::send('emails.tasks.campaigntaskdeleted', $mailData, function ($message) use($mailData) {
	    $message
	      ->to($mailData['assigneeEmail'])
	      ->from('do-not-reply@contentlaunch.com', 'Content Launch')
	      ->subject("{$mailData['someoneHasText']} deleted \"{$mailData['taskName']}\" on " . $mailData['date']->toDayDateTimeString());
	  });
	}

	public function sendCampaignTaskComplete(
		$initiatorFirstName,
		$initiatorLastName,
		$assigneeEmail,
		$taskName,
		$taskDueDate,
		$campaignTitle
	){
		
		$mailData = array(
	    'initiatorFirstName' => $initiatorFirstName,
	    'initiatorLastName' => $initiatorLastName,
	    'taskName' => $taskName,
	    'taskDueDate' => $taskDueDate,
	    'campaignTitle' => $campaignTitle,
	    'assigneeEmail' => $assigneeEmail,
	    'date' => Carbon::now('PST')
	  );

		Mail::send('emails.tasks.campaigntaskcomplete', $mailData, function ($message) use($mailData) {
	    $message
	      ->to($mailData['assigneeEmail'])
	      ->from('do-not-reply@contentlaunch.com', 'Content Launch')
	      ->subject("\"{$mailData['taskName']}\" from the {$mailData['campaignTitle']} campaign has been marked as completed on " . $mailData['date']->toDayDateTimeString());
	  });
	}

	public function sendCampaignTaskReopened(
		$assigneeEmail,
		$taskName,
		$taskDueDate,
		$campaignTitle
	){

		$mailData = array(
	    'assigneeEmail' => $assigneeEmail,
	    'taskName' => $taskName,
	    'taskDueDate' => $taskDueDate,
	    'campaignTitle' => $campaignTitle,
	    'date' => Carbon::now('PST')
	  );

		Mail::send('emails.tasks.campaigntaskreopened', $mailData, function ($message) use($mailData) {
	    $message
	      ->to($mailData['assigneeEmail'])
	      ->from('do-not-reply@contentlaunch.com', 'Content Launch')
	      ->subject("\"{$mailData['taskName']}\" from {$mailData['campaignTitle']} has been reopened and assigned to you on " . $mailData['date']->toDayDateTimeString());
	  });
	}

	public function sendCampaignTaskUpdated(
		$assigneeFirstName,
		$assigneeEmail,
		$orignalTaskName,
		$orignalTaskDueDate,
		$taskName,
		$taskDueDate,
		$campaignTitle
	){
	
		$mailData = array(
			'assigneeFirstName' => $assigneeFirstName,
			'assigneeEmail' => $assigneeEmail,
	    'orignalTaskName' => $orignalTaskName,
	    'orignalTaskDueDate' => $orignalTaskDueDate,
	    'taskName' => $taskName,
	    'taskDueDate' => $taskDueDate,
	    'campaignTitle' => $campaignTitle,
	    'date' => Carbon::now('PST')
	  );

		Mail::send('emails.tasks.campaigntaskupdated', $mailData, function ($message) use($mailData) {
	    $message
	      ->to($mailData['assigneeEmail'])
	      ->from('do-not-reply@contentlaunch.com', 'Content Launch')
	      ->subject("\"{$mailData['orignalTaskName']}\" on the {$mailData['campaignTitle']} campaign has been updated on " . $mailData['date']->toDayDateTimeString());
	  });
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
		$heHas = "{$initiatorFirstName} {$initiatorLastName} has";

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