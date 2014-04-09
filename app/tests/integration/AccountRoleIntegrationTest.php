<?php

use Woodling\Woodling;

class AccountRoleIntegrationTest extends TestCase {

  public function testGetRoles()
  {
    $account = Woodling::saved('Account');
    $roles = Woodling::savedList('AccountRole', 3, array(
      'account_id' => $account->id
    ));
    $response = $this->call('GET', '/api/account/'. $account->id .'/roles');
    $data = $this->assertResponse($response);
    foreach ($data as $key => $role) {
      $this->assertRole($roles[$key], $role);
    }
  }

  public function testSaveRole()
  {
    $account = Woodling::saved('Account');
    $role = Woodling::retrieve('AccountRole', array(
      'account_id' => $account->id
    ));
    $response = $this->call('POST', '/api/account/'. $account->id .'/roles', $role->toArray());
    $data = $this->assertResponse($response);
    $role->id = $data->id;
    $this->assertRole($role, $data);
  }

  public function testSaveRoleDisplayNameExistsForOtherAccountReturnsSuccess()
  {
    $accounts = Woodling::savedList('Account', 2);
    // Attach a role to first account
    $role = Woodling::saved('AccountRole', array(
      'account_id' => $accounts[0]->id
    ));
    // Save a new role for 2nd account with same name
    $role2 = Woodling::retrieve('AccountRole', array(
      'account_id' => $accounts[1]->id,
      'display_name' => $role->display_name
    ));
    $response = $this->call('POST', '/api/account/'. $accounts[1]->id .'/roles', $role2->toArray());
    $data = $this->assertResponse($response);
    $role2->id = $data->id;
    $this->assertRole($role2, $data);
  }

  public function testSaveRoleDisplayNameExistsInAccountReturnsError()
  {
    $account = Woodling::saved('Account');
    $role = Woodling::saved('AccountRole', array(
      'account_id' => $account->id
    ));
    $newRole = Woodling::retrieve('AccountRole', array(
      'display_name' => $role->display_name
    ));
    $response = $this->call('POST', '/api/account/'. $account->id .'/roles', $newRole->toArray());
    $data = $this->assertResponse($response, true);
    $this->assertContains('taken', $data->errors[0]);
  }

  public function testUpdateRole()
  {
    $account = Woodling::saved('Account');
    $role = Woodling::saved('AccountRole', array(
      'account_id' => $account->id
    ));
    $role->display_name = 'Changed Role Name';
    $response = $this->call('PUT', '/api/account/'. $account->id .'/roles/'. $role->id, $role->toArray());
    $data = $this->assertResponse($response);
    $this->assertRole($role, $data);
  }

  public function testUpdateRoleDisplayNameExistsForOtherAccountReturnsSuccess()
  {
    $accounts = Woodling::savedList('Account', 2);
    // Attach a role to first account
    $role = Woodling::saved('AccountRole', array(
      'account_id' => $accounts[0]->id
    ));
    // Attach a role to 2nd account
    $role2 = Woodling::saved('AccountRole', array(
      'account_id' => $accounts[1]->id
    ));
    // Update display name
    $role2->display_name = $role->display_name;
    $response = $this->call('PUT', '/api/account/'. $accounts[1]->id .'/roles/'. $role2->id, $role2->toArray());
    $data = $this->assertResponse($response);
    $role2->id = $data->id;
    $this->assertRole($role2, $data);
  }

  public function testUpdateRoleDisplayNameExistsInAccountReturnsError()
  {
    $account = Woodling::saved('Account');
    $roles = Woodling::savedList('AccountRole', 2, array(
      'account_id' => $account->id
    ));
    $roles[1]->display_name = $roles[0]->display_name;
    $response = $this->call('PUT', '/api/account/'. $account->id .'/roles/'. $roles[1]->id, $roles[1]->toArray());
    $data = $this->assertResponse($response, true);
    $this->assertContains('taken', $data->errors[0]);
  }

  public function testNewAccountAssignsBuiltinRoles()
  {
    // Mimick RoleSeeder, builtin roles should be added to new account
    $roleGlobal = Woodling::saved('Role', array(
      'name' => 'global_admin',
      'global' => 1,
      'builtin' => 0
    ));
    $roleSiteAdmin = Woodling::saved('Role', array(
      'name' => 'site_admin',
      'global' => 0,
      'builtin' => 0
    ));
    $roleBuiltin1 = Woodling::saved('Role', array(
      'global' => 0,
      'builtin' => 1
    ));
    $roleBuiltin2 = Woodling::saved('Role', array(
      'global' => 0,
      'builtin' => 1
    ));
    $account = Woodling::retrieve('Account');
    $response = $this->call('POST', '/api/account', $account->toArray());
    $account = $this->assertResponse($response);
    // Attach a custom role
    $roleCustom = Woodling::saved('AccountRole', array(
      'name' => 'custom_role',
      'display_name' => 'Custom Role',
      'account_id' => $account->id
    ));
    // Now get account roles
    $response = $this->call('GET', '/api/account/'. $account->id .'/roles');
    $data = $this->assertResponse($response);
    // Should have 3 roles
    $this->assertCount(3, $data);
    $roleBuiltin1->id = $data[0]->id;
    $roleBuiltin1->deletable = 0;
    $this->assertRole($roleBuiltin1, $data[0]);
    $roleBuiltin2->id = $data[1]->id;
    $roleBuiltin2->deletable = 0;
    $this->assertRole($roleBuiltin2, $data[1]);
    $this->assertRole($roleCustom, $data[2]);
  }

  public function testDeleteRole()
  {
    $account = Woodling::saved('Account');
    $role = Woodling::saved('AccountRole', array(
      'account_id' => $account->id
    ));
    $response = $this->call('DELETE', '/api/account/'. $account->id .'/roles/'. $role->id);
    $this->assertResponse($response);
    $deleted = DB::table('roles')->where('id', $role->id)->pluck('id');
    $this->assertEmpty($deleted);
  }

  public function testDeleteNonDeletableRoleReturnsError()
  {
    $account = Woodling::saved('Account');
    $role = Woodling::saved('AccountRole', array(
      'account_id' => $account->id,
      'deletable' => 0
    ));
    $response = $this->call('DELETE', '/api/account/'. $account->id .'/roles/'. $role->id);
    $data = $this->assertResponse($response, true, 401);
    $this->assertContains('delete', $data->errors[0]);
  }

}
