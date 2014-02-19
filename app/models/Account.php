<?php

use LaravelBook\Ardent\Ardent;

class Account extends Ardent {

	public static $rules = array(
		'title' => 'required|alpha_num|min:5|unique:accounts'
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
	protected $fillable = array('title');

	/**
	 * Define relationship to another model.
	 * An Account has many User(s).
	 */
	public function users()
	{
		return $this->belongsToMany('User');
	}

}