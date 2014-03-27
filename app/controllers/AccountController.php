<?php

class AccountController extends BaseController {

	public function index()
	{
		return Account::countusers()->with('accountSubscription')->get();
	}

	public function store()
	{
		$account = new Account;
		if ($account->save())
		{
			return $this->show($account->id);
		}
		return $this->responseError($account->errors()->all(':message'));
	}

	public function show($id)
	{
		return Account::countusers()->with('accountSubscription')->where('accounts.id', $id)->first();
	}

	public function update($id)
	{
		$account = Account::find($id);
		if ($account->updateUniques())
		{
			return $this->show($account->id);
		}
		return $this->responseError($account->errors()->all(':message'));
	}

	public function destroy($id)
	{
		$account = Account::find($id);
		if ($account && $account->delete()) {
			return Response::json(array('success' => 'OK'), 200);
		}
		return $this->responseError("Couldn't delete account");
	}

}
