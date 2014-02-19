<?php

class RoleSeeder extends Seeder {

	public function run()
	{
		// Create the roles we know we will need
		$roles = array('Admin', 'Site Admin', 'Content Creator', 'Manager', 'Director', 'C-Level', 'Editor', 'Client');
		foreach ($roles as $roleName) {
			$role = new Role;
			$role->name = $roleName;
			$role->save();
		} 
	}

}
