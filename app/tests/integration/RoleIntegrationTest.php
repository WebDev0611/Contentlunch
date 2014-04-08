<?php

use Woodling\Woodling;

class RoleIntegrationTest extends TestCase {

	public function testGetRoles()
	{
		$roles = Woodling::savedList('Role', 5);
		$response = $this->call('GET', '/api/role');
		$data = $this->assertResponse($response);
		foreach ($data as $key => $role) {
			$this->assertRole($roles[$key], $role);
		}
	}

	public function testShowReturnsRoleRecord()
	{
		$roles = Woodling::savedList('Role', 5);
		$response = $this->call('GET', '/api/role/'. $roles[2]->id);
		$data = $this->assertResponse($response);
		$this->assertRole($roles[2], $data);
	}

	public function testShowNonExistentRoleReturnsError()
	{
		$response = $this->call('GET', '/api/role/999');
		$data = $this->assertResponse($response, true);
		$this->assertContains('found', $data->errors[0]);
	}

	public function testStoreNewRoleReturnsRoleObject()
	{
		$role = Woodling::retrieve('Role');
		$response = $this->call('POST', '/api/role', $role->toArray());
		$data = $this->assertResponse($response);
		$role->id = $data->id;
		$this->assertRole($role, $data);
	}

	public function testStoreExistingRoleReturnsError()
	{
		$role = Woodling::saved('Role');
		$response = $this->call('POST', '/api/role', array(
			'name' => $role->name
		));
		$data = $this->assertResponse($response, true);
		$this->assertContains('taken', $data->errors[0]);
	}

	public function testUpdateRole()
	{
		$role = Woodling::saved('Role');
		$response = $this->call('PUT', '/api/role/'. $role->id, array(
			'name' => 'foobar'
		));
		$role->name = 'foobar';
		$data = $this->assertResponse($response);
		$this->assertRole($role, $data);
	}

	public function testUpdateExistingRoleReturnsError()
	{
		$roles = Woodling::savedList('Role', 3);
		$response = $this->call('PUT', '/api/role/'. $roles[2]->id, array(
			'name' => $roles[1]->name
		));
		$data = $this->assertResponse($response, true);
		$this->assertContains('taken', $data->errors[0]);
	}

	public function testDeleteRole()
	{
		$role = Woodling::saved('Role');
		$response = $this->call('DELETE', '/api/role/'. $role->id);
		$data = $this->assertResponse($response);
		$id = DB::table('roles')->where('id', $role->id)->pluck('id');
		$this->assertEmpty($id);
	}

	public function testDeleteGlobalRoleReturnError()
	{
		$role = Woodling::saved('Role', array(
			'global' => 1
		));
		$response = $this->call('DELETE', '/api/role/'. $role->id);
		$data = $this->assertResponse($response, true, 401);
	}

}
