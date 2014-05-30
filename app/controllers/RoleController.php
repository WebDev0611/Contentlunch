<?php

class RoleController extends BaseController {

	public function index()
	{
    if (Input::get('global')) {
      return Role::where('global', Input::get('global'))->get();
    }
		return Role::all();
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
		// Restrict to global admins
		if ( ! $this->hasRole('global_admin')) {
			return $this->responseAccessDenied();
		}
		$role = Role::find($id);
		$role->display_name = Input::get('display_name');
		$role->status = Input::get('status');
		if ( ! $role) {
			return $this->responseError("Role not found.");
		}
		if ($role->updateUniques())
		{
			return $role;
		}
		return $this->responseError($role->errors()->all(':message'));
	}

}
