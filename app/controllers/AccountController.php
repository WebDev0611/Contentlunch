<?php

class AccountController extends BaseController {

	public function index()
	{
		return Account::all();
	}

	public function store()
	{
		$account = Account::create(Input::all());
		if ($account->isSaved())
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
		$account = Account::update($id);
		if ($account->isSaved())
		{
			return $account;
		}
		return $account->errors();
	}

	public function destroy($id)
	{
		$account = Account::find($id);
		return $account->delete();
	}

}