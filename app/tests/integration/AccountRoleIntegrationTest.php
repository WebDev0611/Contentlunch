<?php

use Woodling\Woodling;

class AccountRoleIntegrationTest extends TestCase {

  public function testGetRoles()
  {
    $account = Woodling::saved('Account');
    $roles = Woodling::savedList('AccountRole', 3, array(
      'account_id' => $account->id
    ));
    $perms = Woodling::savedList('Permission', 3);
    // Attach permissions
    $roles[0]->perms()->sync(array($perms[0]->id, $perms[1]->id, $perms[2]->id));
    $roles[1]->perms()->sync(array($perms[0]->id));
    $response = $this->call('GET', '/api/account/'. $account->id .'/roles');
    $data = $this->assertResponse($response);
    foreach ($data as $key => $role) {
      $this->assertRole($roles[$key], $role);
    }
    // First role should have all permissions set
    // Should show all permissions with access set to true if the role has
    // permission enabled
    $perms = array(
      0 => (object) array('name' => $perms[0]->name, 'display_name' => $perms[0]->display_name, 'access' => true),
      1 => (object) array('name' => $perms[1]->name, 'display_name' => $perms[1]->display_name, 'access' => true),
      2 => (object) array('name' => $perms[2]->name, 'display_name' => $perms[2]->display_name, 'access' => true),
      );
    $this->assertEquals($perms, $data[0]->permissions);
    // 2nd role should have first permission
    $perms[1]->access = false;
    $perms[2]->access = false;
    $this->assertEquals($perms, $data[1]->permissions);
    // 3rd role has no permissions
    $perms[0]->access = false;
    $this->assertEquals($perms, $data[2]->permissions);
  }

  public function testSaveRole()
  {
    $account = Woodling::saved('Account');
    $role = Woodling::retrieve('AccountRole', array(
      'account_id' => $account->id
    ));
    $permissions = Woodling::savedList('Permission', 2);
    // Save this role with the first permission
    $role->permissions = array(array(
      'name' => $permissions[0]->name,
      'display_name' => $permissions[1]->name,
      'access' => true
    ));
    $response = $this->call('POST', '/api/account/'. $account->id .'/roles', $role->toArray());
    $data = $this->assertResponse($response);
    $role->id = $data->id;
    $this->assertRole($role, $data);
    $expected = array(
      (object) array('name' => $permissions[0]->name, 'display_name' => $permissions[0]->display_name, 'access' => true),
      (object) array('name' => $permissions[1]->name, 'display_name' => $permissions[1]->display_name, 'access' => false),
    );
    $this->assertEquals($expected, $data->permissions);
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
    $permissions = Woodling::savedList('Permission', 2);
    // Attach both permissions to role
    $syncPerms = array();
    foreach ($permissions as $permission) {
      $syncPerms[] = $permission->id;
    }
    $role->perms()->sync($syncPerms);
    // Update this role with the first permission
    $role->permissions = array(array(
      'name' => $permissions[0]->name,
      'display_name' => $permissions[0]->display_name,
      'access' => 1
    ), array(
      'name' => $permissions[1]->name,
      'display_name' => $permissions[1]->display_name,
      'access' => 0
    ));
    // Update display name
    $role->display_name = 'Changed Role Name';
    $response = $this->call('PUT', '/api/account/'. $account->id .'/roles/'. $role->id, $role->toArray());
    $data = $this->assertResponse($response);
    $this->assertRole($role, $data);
    $expected = array(
      (object) array('name' => $permissions[0]->name, 'display_name' => $permissions[0]->display_name, 'access' => 1),
      (object) array('name' => $permissions[1]->name, 'display_name' => $permissions[1]->display_name, 'access' => 0),
    );
    $this->assertEquals($expected, $data->permissions);
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
    // Attach permissions
    $perms = Woodling::savedList('Permission', 4);
    $roleBuiltin1->perms()->sync(array($perms[0]->id, $perms[1]->id));
    $roleBuiltin2->perms()->sync(array($perms[2]->id, $perms[3]->id));

    $account = Woodling::retrieve('Account');
    // Create new account, should copy over default roles and their permissions
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
    // Check permissions were copied over from default roles
    $expect = array( (object) array(
      'name' => $perms[0]->name,
      'display_name' => $perms[0]->display_name,
      'access' => 1
    ), (object) array(
      'name' => $perms[1]->name,
      'display_name' => $perms[1]->display_name,
      'access' => 1
    ), (object) array(
      'name' => $perms[2]->name,
      'display_name' => $perms[2]->display_name,
      'access' => 0
    ), (object) array(
      'name' => $perms[3]->name,
      'display_name' => $perms[3]->display_name,
      'access' => 0
    ));
    $this->assertEquals($expect, $data[0]->permissions);
    $expect = array( (object) array(
      'name' => $perms[0]->name,
      'display_name' => $perms[0]->display_name,
      'access' => 0
    ), (object) array(
      'name' => $perms[1]->name,
      'display_name' => $perms[1]->display_name,
      'access' => 0
    ), (object) array(
      'name' => $perms[2]->name,
      'display_name' => $perms[2]->display_name,
      'access' => 1
    ), (object) array(
      'name' => $perms[3]->name,
      'display_name' => $perms[3]->display_name,
      'access' => 1
    ));
    $this->assertEquals($expect, $data[1]->permissions);
  }

  public function testDeleteRole()
  {
    $account = Woodling::saved('Account');
    $role = Woodling::saved('AccountRole', array(
      'account_id' => $account->id
    ));
    $permissions = Woodling::savedList('Permission', 2);
    // Attach both permissions to role
    $syncPerms = array();
    foreach ($permissions as $permission) {
      $syncPerms[] = $permission->id;
    }
    $role->perms()->sync($syncPerms);
    $response = $this->call('DELETE', '/api/account/'. $account->id .'/roles/'. $role->id);
    $this->assertResponse($response);
    $deleted = DB::table('roles')->where('id', $role->id)->pluck('id');
    $this->assertEmpty($deleted);
    // Permission roles should be deleted too
    $deleted = DB::table('permission_role')->where('role_id', $role->id)->get();
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
