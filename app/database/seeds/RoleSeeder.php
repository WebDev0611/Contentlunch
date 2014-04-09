<?php

class RoleSeeder extends Seeder {

	public function run()
	{
        // Create builtin roles: Global Admin, Site Admin
        $role = new Role;
        $role->name = 'global_admin';
        $role->display_name = 'Global Admin';
        $role->global = true;
        $role->deletable = false;
        $role->builtin = false;
        $role->status = 1;
        $role->save();

        $role = new Role;
        $role->name = 'site_admin';
        $role->display_name = 'Site Admin';
        $role->global = false;
        $role->deletable = false;
        $role->builtin = false;
        $role->status = 1;
        $role->save();

        $role = new Role;
        $role->name = 'manager';
        $role->display_name = 'Manager / Director';
        $role->global = false;
        $role->deletable = false;
        $role->builtin = true;
        $role->status = 1;
        $role->save();

        $role = new Role;
        $role->name = 'creator';
        $role->display_name = 'Creator / Author';
        $role->global = false;
        $role->deletable = false;
        $role->builtin = true;
        $role->status = 1;
        $role->save();

        $role = new Role;
        $role->name = 'client';
        $role->display_name = 'Client';
        $role->global = false;
        $role->deletable = false;
        $role->builtin = true;
        $role->status = 1;
        $role->save();

        $role = new Role;
        $role->name = 'editor';
        $role->display_name = 'Editor';
        $role->global = false;
        $role->deletable = false;
        $role->builtin = true;
        $role->status = 1;
        $role->save();

	}

}
