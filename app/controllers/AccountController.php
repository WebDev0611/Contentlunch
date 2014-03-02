<?php

class AccountController extends BaseController {

	public function index()
	{
		return Account::all();
	}

	public function store()
	{
		$account = new Account;
		if ($account->save())
		{
			return $account;
		}
		return $account->errors();
	}

	public function show($id)
	{
		return Account::find($id);
	}

	public function update($id)
	{
		$account = Account::find($id);
		if ($account->updateUniques())
		{
			return $account;
		}
		return $account->errors();
	}

	public function destroy($id)
	{
		$account = Account::find($id);
		if ($account->delete()) {
			return Response::json(array('success' => 'OK'), 200);
		}
		return Response::json(array('message' => "Couldn't delete account"), 401);
	}

}