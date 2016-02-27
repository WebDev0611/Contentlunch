<?php

class AgencyController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index($accountId)
	{
		if (!$this->inAccount($accountId)) {
			return $this->responseAccessDenied();
		}

		$agencyAccount = Account::find($accountId);
		if($agencyAccount->account_type != 'agency') {
			return Response::json(['errors' => ['Not an agency account, no clients allowed.']], 401);
		}

		return $agencyAccount->children()->get();
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create($accountId)
	{


	}


	/**
	 * Create a new client for this agency
	 *
	 * @return Response
	 */
	public function store($accountId)
	{
		$account = new Account;
		DB::transaction(function() use ($accountId, $account) {
			$agencyAccount = Account::find($accountId);
			if($agencyAccount->account_type != 'agency') {
				return Response::json(['errors' => ['Not an agency account, no clients allowed.']], 401);
			}

			// TODO - check access


			$clientName = Input::get('name');
			$contactName = Input::get('clientName');
			$contactEmail = Input::get('clientEmail');

			$names = explode(" ", $contactName, 2);
			if(count($names) == 2) {
				list($firstName, $lastName) = $names;
			} else {
				$firstName = $contactName;
				$lastName = "";
			}

			$account->expiration_date = \Carbon\Carbon::now()->addDays(30);
			$account->name = $clientName;
			$account->email = $contactEmail;
			$account->account_type = 'client';
			$account->parent_id = $agencyAccount->id;
			$account->save();

			$accountController = App::make('AccountController');
			$accountController->createRoles($account);
			$accountController->createAccountSettings($account);

			App::make('AccountSubscriptionController')->create_subscription($account->id, 3, 999, 0, 0, 1,
					  'API, Premium Support, Custom Reporting, Advanced Security', "client");
		});
		return $account;
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($accountId, $id)
	{
		if (!$this->inAccount($accountId)) {
			return $this->responseAccessDenied();
		}

		$agencyAccount = Account::find($accountId);
		if($agencyAccount->account_type != 'agency') {
			return Response::json(['errors' => ['Not an agency account, no clients allowed.']], 401);
		}

		return $agencyAccount->children()->where('id', $id)->first();
	}



	/**
	 * Remove the specified account as a client.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($accountId, $id)
	{
		if (!$this->inAccount($accountId)) {
			return $this->responseAccessDenied();
		}

		$agencyAccount = Account::find($accountId);
		if($agencyAccount->account_type != 'agency') {
			return Response::json(['errors' => ['Not an agency account, no clients allowed.']], 401);
		}

		$account = $agencyAccount->children()->where('id', $id)->first();
		$account->parent_id = null;
		$account->save();
		return $account;
	}


}
