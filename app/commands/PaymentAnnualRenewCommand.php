<?php

use Indatus\Dispatcher\Scheduling\ScheduledCommand;
use Indatus\Dispatcher\Scheduling\Schedulable;
use Indatus\Dispatcher\Drivers\Cron\Scheduler;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use Launch\Repositories\AccountRepository;
use Launch\Repositories\EmailRepository;
use Launch\Balanced;

class PaymentAnnualRenewCommand extends ScheduledCommand {

	protected $name = 'contentlaunch:paymentannualrenew';

	protected $description = 'Charges annual accounts set to auto-renew';

	protected $accountRepo;
	protected $emailRepo;

	public function user()
  {
      return 'root';
  }

	public function __construct()
	{
		parent::__construct();
		$this->accountRepo = new AccountRepository;
		$this->emailRepo = new EmailRepository;
	}

	public function schedule(Schedulable $scheduler)
	{
		return $scheduler;
		return $scheduler
      ->daily()
      ->hours(16)
      ->minutes(5);
	}

	public function fire()
	{
		if ($accounts = $this->accountRepo->getDueAnnualRenewAccounts()) {
			foreach ($accounts as $account) {
				try {
					$balancedAccount = new Balanced($account);
					$ammountCharged = $balancedAccount->charge($account);
				} catch (\Balanced\Errors\Error $e) {
	    		$this->accountRepo->flagAccountForBillingError($account);
	      	$this->emailRepo->sendBillingError($account);
	      	Log::info("PaymentAnnualRenewCommand: payment rejected for {$account->name}");
	    	}
	    	$this->emailRepo->sendBillingInvoice($account, $ammountCharged);
	    	$this->accountRepo->renewAnnualRenewAccount($account);
	    	Log::info("PaymentAnnualRenewCommand: payment succeeded for {$account->id}");
			}
			$count = count($accounts);
			Log::info("Success: PaymentAnnualRenewCommand: Handled $count accounts");
		}
	}

}
