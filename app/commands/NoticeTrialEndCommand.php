<?php

use Indatus\Dispatcher\Scheduling\ScheduledCommand;
use Indatus\Dispatcher\Scheduling\Schedulable;
use Indatus\Dispatcher\Drivers\Cron\Scheduler;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use Launch\Repositories\AccountRepository;
use Launch\Repositories\EmailRepository;

class NoticeTrialEndCommand extends ScheduledCommand {

	protected $name = 'contentlaunch:trialend';

	protected $description = 'Detects ended Trial accounts, sends notification email';

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
		if ($accounts = $this->accountRepo->getEndedTrialAccounts()) {
			foreach ($accounts as $account) {
				$this->emailRepo->sendTrialHasEnded($account);
			}
			$count = count($accounts);
			Log::info("Success: NoticeTrialEndCommand: Handled $count accounts");
		} else {
			Log::info("Success: NoticeTrialEndCommand: Handled 0 accounts");
		}
	}
}
