<?php

use LaravelBook\Ardent\Ardent;

class Account extends Ardent {

	// Hydrates from input on new entry's validation
	public $autoHydrateEntityFromInput = true;
	// Hydrates from input whenver validation is called
	public $forceEntityHydrationFromInput = true;

	public static $rules = [
		//'title' => 'required|min:5|unique:accounts',
		'name' => 'required|min:5',
        'email' => 'unique:accounts'
	];

	public static $customMessages = [
		'title.unique' => 'The account name has already been taken.'
	];

	protected $table = 'accounts';

	protected $hidden = ['balanced_info'];

  protected $softDelete = true;

	/**
	 * Specifies the columns that can be mass assigned
	 *
	 * @var array
	 */
	protected $fillable = [
		'title', 'active', 'address', 'address_2', 'name', 'city',
		'state', 'phone', 'country', 'zipcode', 'email', 'auto_renew',
		'payment_type', 'token', 'yearly_payment', 'strategy'
  ];

	//protected function getDateFormat()
 // {
 //   return 'Y-m-d H:i:s';
 // }

  protected function beforeSave()
  {
  	if (app()->env != 'testing') {
	  	// If any "customer" info changes, update it in balanced
      try {
  	  	if ($this->isDirty('title')) {
  	  		$balancedAccount = new Launch\Balanced($this);
  	  		// This will sync the customer details with balanced
  	  		$balancedAccount->syncCustomer();
  	  	}
  	  	// If the token has changed, we are saving a new credit card or bank account
  	  	if ($this->isDirty('token')) {
  	  		$balancedAccount = new Launch\Balanced($this);
  	  		// This will sync the payment details with balanced
  	  		$balancedAccount->syncPayment();
  	  	}
      } catch (\Exception $e) {
        
      }
  	}

    if (is_array(@$this->strategy)) {
      $this->strategy = json_encode($this->strategy);
    }
  }

    public function toArray()
    {
      $values = parent::toArray();

      if (is_string(@$values['strategy'])) {
        $values['strategy'] = @json_decode($values['strategy'], true);
      }
      if (!@$values['strategy']) {
        $values['strategy'] = [];
      }

      return $values;
    }

  /**
   * Return the newest subscription record
   */
  public function accountSubscription()
  {
  	return $this->hasOne('AccountSubscription', 'account_id', 'id')->orderBy('id', 'desc');
  }

  public function modules()
  {
  	return $this->belongsToMany('Module');
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
			DB::raw("(
                SELECT account_id, COUNT(*) AS count_users
                FROM account_user
                INNER JOIN users ON users.id = account_user.user_id
                WHERE users.deleted_at IS NULL
                GROUP BY account_id
			) usercount"),
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

	/**
	 * Get the site admin user for the account (should be 1)
	 * @return object $user
	 */
	public function getSiteAdminUser()
	{
    foreach ($this->users as $user) {
      if ($user->roles) {
        foreach ($user->roles as $role) {
          if ($role->name == 'site_admin') {
            return $user;
          }
        }
      }
    }
	}

}
