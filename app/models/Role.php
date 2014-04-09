<?php

use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole
{

	public $forceEntityHydrationFromInput = false;

	public $autoHydrateEntityFromInput = false;

	public static $rules = array(
		// Check for uniques where account_id IS NULL, so that
		// this applies only to built in roles
		'name' => 'required|unique:roles,name,NULL,id,account_id,NULL',
		'display_name' => 'required|unique:roles,display_name,NULL,id,account_id,NULL'
	);

	/**
	 * Get a role model by name
	 *
	 * @param  string $name
	 * @return object
	 */
	public static function find_by_name($name) {
		$role = static::where('name', '=', $name)->first();
		if ( ! $role) {
			throw new \Exception('Role not found: '. $name);
		}
		return $role;
	}

}
