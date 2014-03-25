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
		return $this->responseError($role->errors()->all(':message'));
	}

	public function show($id)
	{
		$role = Role::find($id);
		if ($role) {
			return $role;
		}
		return $this->responseError("Role not found.");
	}

	public function update($id)
	{
		$role = Role::find($id);
		if ( ! $role) {
			return $this->responseError("Role not found.");
		}
		if ($role->updateUniques())
		{
			return $role;
		}
		return $this->responseError($role->errors()->all(':message'));
	}

	public function destroy($id)
	{
		$role = Role::find($id);
		if ($role->delete()) {
			return array('success' => 'OK');
		}
		return $this->responseError("Couldn't delete role.");
	}

}
