<?php

class HasOffers extends \Eloquent {

	const STATUS_PENDING = 0;
	const STATUS_SUCCESS = 1;
	const STATUS_FAIL = 2;

	const COOKIE_TRANSACTION_ID = 'hf_transaction_id';
	const COOKIE_AFFILATE_ID = 'hf_affiliate_id';
	const COOKIE_OFFER_ID = 'hf_offer_id';

	protected $table = 'hasoffers';

	protected $fillable = [
		'account_id',
		'transaction_id',
		'affiliate_id',
		'offer_id',
		'status'
	];

	public function account()
	{
		return $this->belongTo('Account');
	}
}