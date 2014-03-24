<?php

use LaravelBook\Ardent\Ardent;

class Account extends Ardent {

	// Hydrates from input on new entry's validation
	public $autoHydrateEntityFromInput = true;
	// Hydrates from input whenver validation is called
	public $forceEntityHydrationFromInput = true;

	public static $rules = array(
		'title' => 'required|min:5|unique:accounts'
	);

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'accounts';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array();

	/**
	 * Specifies the columns that can be mass assigned
	 *
	 * @var array
	 */
	protected $fillable = array('title', 'active', 'address', 'address_2', 'name', 'city',
		'state', 'phone', 'country', 'zipcode', 'email');

	protected function getDateFormat()
  {
    return 'Y-m-d H:i:s';
  }

  public function accountSubscription()
  {
  	return $this->hasOne('AccountSubscription', 'account_id', 'id');
  }

	/**
	 * Define relationship to another model.
	 * An Account has many User(s).
	 */
	public function users()
	{
		return $this->belongsToMany('User')->withTimestamps();
	}

	public function scopeCountusers($query)
	{

		return $query->leftJoin(
			DB::raw("(SELECT account_id, COUNT(*) AS count_users FROM account_user GROUP BY account_id) usercount"),
			function ($join) {
				$join->on('accounts.id', '=', 'usercount.account_id');
			}
		)
		->select(array(
			'accounts.*',
			'usercount.count_users'
		));
	}

	/**
	 * Attach a user to this account
	 * @param  integer $id User id
	 */
	public function add_user($id)
	{
		$this->users()->attach($id);
	}

	public function getUsers() {
		return $this->users()->with('roles')->with('accounts')->with('image')->get();
	}

}
