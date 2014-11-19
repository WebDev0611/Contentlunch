<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use Launch\Balanced;
use Launch\Repositories\AccountRepository;

class BalancedTransferCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'contentlaunch:balancedtransfer';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Removes payment info from test market place,
	creates new balanced customers, clears payments table, resets 
	expiration_date';

	protected $accountRepo;

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
		$this->accountRepo = new AccountRepository;
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$accounts = $this->accountRepo->getAllAccounts();

		foreach ($accounts as $account) {
			$this->processConversion($account);
		}

		// Ensure that no accounts exist with set payment info
		$accounts = $this->accountRepo->getAllAccountsWithSetToken();

		foreach ($accounts as $account) {
			$this->processConversion($account);
		}

		$this->resetPaymentsTable();
	}

	private function processConversion($account)
	{
		$this->accountRepo->clearPaymentInfo($account);
		$this->createBalancedCustomer($account);
	}

	private function createBalancedCustomer($account)
	{
		$balancedAccount = new Balanced($account);
		$balancedAccount->syncCustomer();
	}

	private function resetPaymentsTable()
	{
		Payment::truncate();
	}

}
