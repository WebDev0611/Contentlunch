<?php namespace Launch\Repositories;

use \Account;
use \Carbon\Carbon;

class AccountRepository {

	public function getActiveAccounts()
	{
		return Account::whereActive(1)->get();
	}

	public function getAllAccounts()
	{
		return Account::all();
	}

	public function getActiveAccountsWithSetToken()
	{
		return Account::
			       whereActive(1)
			     ->whereNotNull('token')
			     ->get();
	}

	public function getAllAccountsWithSetToken()
	{
		return Account::
			       whereNotNull('token')
			     ->get();
	}

	public function getAccountsNearingTrialEnd()
	{
		$accounts = Account::
	    whereActive(1)
	    ->wherePaymentDate(null)
	    ->whereToken(null)
	    ->whereRaw('DATE(created_at) = DATE_SUB(CURDATE(), INTERVAL 23 DAY)')
	    ->get();
	}

	public function getEndedTrialAccounts() 
	{
		$accounts = Account::
	    whereActive(1)
	    ->wherePaymentDate(null)
	    ->whereToken(null)
	    ->whereExpirationDate(Carbon::today()->toDateString())
	    ->get();
	}

	/* 
		- Nullify Balanced CARD href
	  - Nullify Balanced CUSTOMER href
	  - Nullify saved CC info
	  - Nullify payment_date
	  - Set expiration_date for 30 day trial
	*/
	public function clearPaymentInfo($account)
	{
		$account->token = NULL;
		$account->balanced_info = NULL;
		$account->payment_info = NULL;
		$account->payment_date = NULL;
		$account->expiration_date = '2014-12-31 12:00:00';
		$account->updateUniques();
	}

	/*
		The account token is set in tandem with payment_date,
		so if we have a payment_date set but the account's
		token is null, then there was a problem processing
		their payment. This can be determined with 
		Account::hasMissedPayment().

		- Nullify the Balanced CARD href
		- Nullify saved CC info
	*/
	public function flagAccountForBillingError($account) 
	{
		$account->token = NULL;
		$account->payment_info = NULL;
		$account->updateUniques();
	}

	# Monthly


	/*
		Returns accounts with a monthly, renewing
		subscription whose payment is due today, ie,
		when this function is run.
	*/
	public function getDueMonthlyRenewAccounts()
	{
		return Account::
	      whereActive(1)
	    ->wherePaymentDate(Carbon::today()->toDateString())
	    ->whereYearlyPayment(0)
	    ->whereAutoRenew(1)
	    ->whereNotNull('token')
	    ->get();
	}

	/*
		Sets the account's expiration and payment dates one
		month ahead.
	*/
	public function renewMonthlyAccount($account)
	{
		$account->expiration_date = Carbon::now()->addMonth()->toDateTimeString();
		$account->payment_date = Carbon::now()->addMonth()->toDateString();
		$account->updateUniques();	
	}

	# Annual

	/*
		Returns accounts with an annual, renewing payment
		plan whose payment is due today, ie, when this
		function is run.
	*/
	public function getDueAnnualRenewAccounts()
	{
		return Account::
	      whereActive(1)
	    ->wherePaymentDate(Carbon::today()->toDateString())
	    ->whereYearlyPayment(1)
	    ->whereAutoRenew(1)
	    ->whereNotNull('token')
	    ->get();
	}

	/*
		Returns accounts with an annual, non-renewing payment
		plan whose payment is due today, ie, when this
		function is run.
	*/
	public function getDueAnnualNoRenewAccounts()
	{
		return Account::
	      whereActive(1)
	    ->wherePaymentDate(Carbon::today()->toDateString())
	    ->whereYearlyPayment(1)
	    ->whereAutoRenew(0)
	    ->whereNotNull('token')
	    ->get();
	}

	/*
		Sets the account's expiration and payment dates one
		year ahead.
	*/
	public function renewAnnualRenewAccount($account)
	{
		$account->expiration_date = Carbon::now()->addYear()->toDateTimeString();
		$account->payment_date = Carbon::now()->addYear()->toDateString();
		$account->updateUniques();	
	}

	/*
		Sets the account's expiration date one
		year ahead. Since no payment_date is scheduled,
		we leave it as is.
	*/
	public function renewAnnualNoRenewAccount($account)
	{
		$account->expiration_date = Carbon::now()->addYear()->toDateTimeString();
		$account->updateUniques();	
	}

}