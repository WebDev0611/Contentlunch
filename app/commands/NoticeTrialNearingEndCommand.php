<?php

use Indatus\Dispatcher\Scheduling\ScheduledCommand;
use Indatus\Dispatcher\Scheduling\Schedulable;
use Indatus\Dispatcher\Drivers\Cron\Scheduler;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use Launch\Repositories\AccountRepository;
use Launch\Repositories\EmailRepository;

class NoticeTrialNearingEndCommand extends ScheduledCommand {

	protected $name = 'contentlaunch:trialnearingend';

	protected $description = 'Detects accounts nearing the end of their trial period and send out notifications.';

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
      ->minutes(15);
	}

	public function fire()
	{
		if ($accounts = $this->accountRepo->getAccountsNearingTrialEnd()) {
			foreach ($accounts as $account) {
				$this->emailRepo->sendTrialWarning($account);
			}
			$count = count($accounts);
			Log::info("Success: NoticeTrialNearingEndCommand: Handled $count accounts");
		} else {
			Log::info("Success: NoticeTrialNearingEndCommand: Handled 0 accounts");
		}
	}

}
