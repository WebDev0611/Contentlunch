<?php

class RoleController extends BaseController {

	public function index()
	{
		return Role::all();
	}

	public function store()
	{
		$role = new Role;
		if ($role->save())
		{
			return $role;
		}
		return Response::json(array(
			'message' => "Couldn't store new role",
			'errors' => $role->errors()->toArray()
			), 401);
	}

	public function show($id)
	{
		return Role::find($id);
	}

	public function update($id)
	{
		$role = Role::find($id);
		if ($role->updateUniques())
		{
			return $role;
		}
		return Response::json(array(
			'message' => "Couldn't update role",
			'errors' => $role->errors()->toArray()
		), 401);
	}

	public function destroy($id)
	{
		$role = Role::find($id);
		if ($role->delete()) {
			return Response::json(array('success' => 'OK'), 200);
		}
		return Response::json(array('message' => "Couldn't delete role"), 401);
	}

}