<?php

use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole
{

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
