<?php

class AccountController extends BaseController {

	public function index()
	{
		$accounts = Account::all();
		return Response::json($accounts->toArray(), 200);
	}

	public function store()
	{
		$account = new Account;
		if ($account->save())
		{
			return $this->show($account->id);
		}
		return Response::json(array(
			'errors' => $account->errors()->toArray()
			), 401);
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
			return $this->show($account->id);
		}
		return Response::json(array(
			'errors' => $account->errors()->toArray()
			), 401);
	}

	public function destroy($id)
	{
		$account = Account::find($id);
		if ($account && $account->delete()) {
			return Response::json(array('success' => 'OK'), 200);
		}
		return Response::json(array('message' => "Couldn't delete account"), 401);
	}

	public function add_user($id) {
		$account = Account::find($id);
		$account->add_user(Input::get('user_id'));
		return array('message' => 'OK');
	}

	public function get_users($id)
	{
		$account = Account::find($id);
		return $account->getUsers();
	}

}
