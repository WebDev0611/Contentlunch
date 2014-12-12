<?php

class HasOffersController extends BaseController {

	public function createCookies()
	{
		$cookieLifeSpan = 120;
  		$response = Redirect::to('http://contentlaunch.com');

	  	// Don't collect other cookies if the transaction_id
	  	// doesn't exist. The other cookies are optional.
	  	if ($transaction_id = Input::get('transaction_id')) {

		    $response->headers->setCookie(Cookie::make('hf_transaction_id', $transaction_id, $cookieLifeSpan));

		    if ($offer_id = Input::get('offer_id')) {
		    	$response->headers->setCookie(Cookie::make('hf_offer_id', $offer_id, $cookieLifeSpan));
		    }

		    if ($affiliate_id = Input::get('affiliate_id')) {
		      	$response->headers->setCookie(Cookie::make('hf_affiliate_id', $affiliate_id, $cookieLifeSpan));
		    }
	  	}

	  	return $response;
	}

	public function store($accountId)
	{
		if ($transaction_id = Cookie::get('hf_transaction_id')) {

			$hasOffers = new HasOffers();
			$hasOffers->account_id = $accountId;
			$hasOffers->transaction_id = $transaction_id;
			$hasOffers->status = HasOffers::STATUS_PENDING;

			if ($offer_id = Cookie::get('hf_offer_id')) {
				$hasOffers->offer_id = $offer_id;
			}

			if ($affiliate_id = Cookie::get('hf_affiliate_id')) {
				$hasOffers->affiliate_id = $affiliate_id;
			}

			$hasOffers->save();
		}
	}

	public function test($param)
	{
		echo $param;
	}

}