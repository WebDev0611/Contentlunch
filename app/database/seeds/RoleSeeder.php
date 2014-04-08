<?php

class RoleSeeder extends Seeder {

	public function run()
	{
    // Create 2 builtin roles: Global Admin, Site Admin
    $role = new Role;
    $role->display_name = 'Global Admin';
    $role->name = 'global_admin';
    $role->global = 1;
    $role->save();

    $role = new Role;
    $role->display_name = 'Site Admin';
    $role->name = 'site_admin';
    $role->global = 1;
    $role->save();
    
	}

}
