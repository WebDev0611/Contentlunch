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

	public function sendTest() {
		echo 'sending...';

		$mailData = [];

		Mail::send('emails.tasks.newtask', $mailData, function ($message) {
	    $message
	      ->to('brianthesmall@gmail.com')
	      ->from('do-not-reply@contentlaunch.com', 'Content Launch')
	      ->subject("Format With Link Again");
	  });
	}

	public function sendContentTaskReminder(
		$assigneeEmail,
		$assigneeFirstName,
		$taskName,
		$taskDueDate,
		$contentTitle,
		$contentId
	){

		$taskDueDate = new Carbon($taskDueDate);
		$taskDueDate = $taskDueDate->toFormattedDateString();

		$mailData = array(
	    'assigneeFirstName' => $assigneeFirstName,
	    'assigneeEmail' => $assigneeEmail,
	    'taskName' => $taskName,
	    'taskDueDate' => $taskDueDate,
	    'contentTitle' => $contentTitle,
	    'contentUrl' => \URL::to("create/content/edit/{$contentId}"),
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
		$campaignTitle,
		$campaignId
	){

		$taskDueDate = new Carbon($taskDueDate);
		$taskDueDate = $taskDueDate->toFormattedDateString();

		$mailData = array(
	    'assigneeFirstName' => $assigneeFirstName,
	    'assigneeEmail' => $assigneeEmail,
	    'taskName' => $taskName,
	    'taskDueDate' => $taskDueDate,
	    'campaignTitle' => $campaignTitle,
	    'campaignUrl' => \URL::to("calendar/campaigns/{$campaignId}"),
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
		$campaignTitle,
		$campaignId
	){

		$taskDueDate = new Carbon($taskDueDate);
		$taskDueDate = $taskDueDate->toFormattedDateString();

		$mailData = array(
	    'assigneeFirstName' => $assigneeFirstName,
	    'assigneeEmail' => $assigneeEmail,
	    'taskName' => $taskName,
	    'taskDueDate' => $taskDueDate,
	    'campaignTitle' => $campaignTitle,
	    'campaignUrl' => \URL::to("calendar/campaigns/{$campaignId}"),
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
		$campaignTitle,
		$campaignId
	){

		$taskDueDate = new Carbon($taskDueDate);
		$taskDueDate = $taskDueDate->toFormattedDateString();

		$mailData = array(
	    'assigneeFirstName' => $assigneeFirstName,
	    'assigneeEmail' => $assigneeEmail,
	    'taskName' => $taskName,
	    'taskDueDate' => $taskDueDate,
	    'campaignTitle' => $campaignTitle,
	    'campaignUrl' => \URL::to("calendar/campaigns/{$campaignId}"),
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
		$campaignTitle,
		$campaignId
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

		$taskDueDate = new Carbon($taskDueDate);
		$taskDueDate = $taskDueDate->toFormattedDateString();

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
	    'someoneHasText' => $someoneHasText,
	    'campaignUrl' => \URL::to("calendar/campaigns/{$campaignId}"),
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
		$initiatorId,
		$assigneeId,
		$assigneeEmail,
		$taskName,
		$taskDueDate,
		$campaignTitle,
		$campaignId
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

		$taskDueDate = new Carbon($taskDueDate);
		$taskDueDate = $taskDueDate->toFormattedDateString();
		
		$mailData = array(
	    'initiatorFirstName' => $initiatorFirstName,
	    'initiatorLastName' => $initiatorLastName,
	    'taskName' => $taskName,
	    'taskDueDate' => $taskDueDate,
	    'campaignTitle' => $campaignTitle,
	    'assigneeEmail' => $assigneeEmail,
	    'someoneHas' => $someoneHasText,
	    'campaignUrl' => \URL::to("calendar/campaigns/{$campaignId}"),
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
		$campaignTitle,
		$campaignId
	){

		$taskDueDate = new Carbon($taskDueDate);
		$taskDueDate = $taskDueDate->toFormattedDateString();

		$mailData = array(
	    'assigneeEmail' => $assigneeEmail,
	    'taskName' => $taskName,
	    'taskDueDate' => $taskDueDate,
	    'campaignTitle' => $campaignTitle,
	    'campaignUrl' => \URL::to("calendar/campaigns/{$campaignId}"),
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
		$campaignTitle,
		$campaignId
	){

		$formattedTaskDueDate = new Carbon($taskDueDate);
		$formattedTaskDueDate = $formattedTaskDueDate->toFormattedDateString();
	
		$mailData = array(
			'assigneeFirstName' => $assigneeFirstName,
			'assigneeEmail' => $assigneeEmail,
	    'orignalTaskName' => $orignalTaskName,
	    'orignalTaskDueDate' => $orignalTaskDueDate,
	    'taskName' => $taskName,
	    'taskDueDate' => $taskDueDate,
	    'formattedTaskDueDate' => $formattedTaskDueDate,
	    'campaignTitle' => $campaignTitle,
	    'campaignUrl' => \URL::to("calendar/campaigns/{$campaignId}"),
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
		$contentTitle,
		$contentId
	){

		$taskDueDate = new Carbon($taskDueDate);
		$taskDueDate = $taskDueDate->toFormattedDateString();

		$mailData = array(
	    'assigneeFirstName' => $assigneeFirstName,
	    'assigneeEmail' => $assigneeEmail,
	    'taskName' => $taskName,
	    'taskDueDate' => $taskDueDate,
	    'contentTitle' => $contentTitle,
	    'contentUrl' => \URL::to("create/content/edit/{$contentId}"),
	    'date' => Carbon::now('PST')
	  );

	  Mail::send('emails.tasks.newcontenttask', $mailData, function ($message) use($mailData) {
	    $message
	      ->to($mailData['assigneeEmail'])
	      ->from('do-not-reply@contentlaunch.com', 'Content Launch')
	      ->subject("You have been assigned a new Task on " . $mailData['date']->toDayDateTimeString());
	  });
	}

	public function sendContentTaskRemoval(
		$assigneeFirstName,
		$assigneeEmail,
		$taskName,
		$taskDueDate,
		$contentTitle,
		$contentId
	){

		$taskDueDate = new Carbon($taskDueDate);
		$taskDueDate = $taskDueDate->toFormattedDateString();

		$mailData = array(
	    'assigneeFirstName' => $assigneeFirstName,
	    'assigneeEmail' => $assigneeEmail,
	    'taskName' => $taskName,
	    'taskDueDate' => $taskDueDate,
	    'contentTitle' => $contentTitle,
	    'contentUrl' => \URL::to("create/content/edit/{$contentId}"),
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
		$contentTitle,
		$contentId
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

		$taskDueDate = new Carbon($taskDueDate);
		$taskDueDate = $taskDueDate->toFormattedDateString();

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
	    'someoneHasText' => $someoneHasText,
	    'contentUrl' => \URL::to("create/content/edit/{$contentId}"),
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
		$initiatorId,
		$assigneeId,
		$assigneeEmail,
		$taskName,
		$taskDueDate,
		$contentTitle,
		$contentId
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

		$taskDueDate = new Carbon($taskDueDate);
		$taskDueDate = $taskDueDate->toFormattedDateString();
		
		$mailData = array(
	    'initiatorFirstName' => $initiatorFirstName,
	    'initiatorLastName' => $initiatorLastName,
	    'taskName' => $taskName,
	    'taskDueDate' => $taskDueDate,
	    'contentTitle' => $contentTitle,
	    'assigneeEmail' => $assigneeEmail,
	    'someoneHas' => $someoneHasText,
	    'contentUrl' => \URL::to("create/content/edit/{$contentId}"),
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
		$contentTitle,
		$contentId
	){

		$taskDueDate = new Carbon($taskDueDate);
		$taskDueDate = $taskDueDate->toFormattedDateString();

		$mailData = array(
	    'assigneeEmail' => $assigneeEmail,
	    'taskName' => $taskName,
	    'taskDueDate' => $taskDueDate,
	    'contentTitle' => $contentTitle,
	    'contentUrl' => \URL::to("create/content/edit/{$contentId}"),
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
		$contentTitle,
		$contentId
	){

		$orignalTaskDueDate = new Carbon($orignalTaskDueDate);
		$orignalTaskDueDate = $orignalTaskDueDate->toFormattedDateString();
		$taskDueDate = new Carbon($taskDueDate);
		$taskDueDate = $taskDueDate->toFormattedDateString();
	
		$mailData = array(
			'assigneeFirstName' => $assigneeFirstName,
			'assigneeEmail' => $assigneeEmail,
	    'orignalTaskName' => $orignalTaskName,
	    'orignalTaskDueDate' => $orignalTaskDueDate,
	    'taskName' => $taskName,
	    'taskDueDate' => $taskDueDate,
	    'contentTitle' => $contentTitle,
	    'contentUrl' => \URL::to("create/content/edit/{$contentId}"),
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

		$paymentInfo = unserialize($account->payment_info);

		$lastFour = substr($paymentInfo['card_number'], -4);

	  $mailData = array(
	    'accountName' => $account->name,
	    'cardType' => $paymentInfo['card_type'],
	    'lastFour' => $lastFour,
	    'loginUrl' => \URL::to("login")
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

  	$amountCharged = number_format($amountCharged / 100, 2, '.', '');

  	$cardType = $paymentInfo['card_type'];
  	$lastFour = substr($paymentInfo['card_number'], -4);

  	$user = $account->getSiteAdminUser();

		$mailData = array(
	    'amountCharged' => $amountCharged,
	    'cardInfo' => "$cardType $lastFour",
	    'accountName' => "{$user->first_name} {$user->last_name}",
	    'tier' => "Tier {$subscription->subscription_level}",
	    'dateBilled' => Carbon::now()->toFormattedDateString()
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