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

	public function testUpdateRole()
	{
		$role = Woodling::saved('Role');
		$role->display_name = 'Foobar';
		$role->status = 2;
		$response = $this->call('PUT', '/api/role/'. $role->id, $role->toArray());
		$data = $this->assertResponse($response);
		$this->assertRole($role, $data);
	}

	public function testUpdateExistingDisplayNameRoleReturnsError()
	{
		$roleOne = Woodling::saved('Role', array(
			'builtin' => true
		));
		$roleTwo = Woodling::saved('Role', array(
			'builtin' => true
		));
		$roleTwo->display_name = $roleOne->display_name;
		$response = $this->call('PUT', '/api/role/'. $roleTwo->id, $roleTwo->toArray());
		$data = $this->assertResponse($response, true);
		$this->assertContains('taken', $data->errors[0]);
	}

}
