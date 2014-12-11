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
		$assigneeLastName,
		$taskName,
		$taskDueDate,
		$contentTitle,
		$contentId
	){

		$taskDueDate = new Carbon($taskDueDate);
		$taskDueDate = $taskDueDate->toFormattedDateString();

		$mailData = array(
	    'assigneeFirstName' => $assigneeFirstName,
	    'assigneeLastName' => $assigneeLastName,
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
		$assigneeLastName,
		$taskName,
		$taskDueDate,
		$campaignTitle,
		$campaignId
	){

		$taskDueDate = new Carbon($taskDueDate);
		$taskDueDate = $taskDueDate->toFormattedDateString();

		$mailData = array(
	    'assigneeFirstName' => $assigneeFirstName,
	    'assigneeLastName' => $assigneeLastName,
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