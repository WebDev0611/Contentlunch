<?php

use Zizaco\Entrust\EntrustRole;

class AccountRole extends EntrustRole
{

	public $forceEntityHydrationFromInput = false;

	public $autoHydrateEntityFromInput = true;

	public static $rules = array(
		'account_id' => 'required'
	);

	/**
	 * Override validate, dynamically set validation rules
	 */
	public function validate(array $rules = array(), array $customMessages = array())
	{
		// Start with static rules
		$rules = static::$rules;
		// Check for uniques where account_id is the same, so that
		// this applies only to an account context
		// Add this record's id to the unique check to skip it if updating
		$rules['name'] = 'required|unique:roles,name,'. $this->id .',id,account_id,'. $this->account_id;
		$rules['display_name'] = 'required|unique:roles,display_name,'. $this->id .',id,account_id,'. $this->account_id;
		return parent::validate($rules, $customMessages);
	}

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
