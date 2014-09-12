<?php

use LaravelBook\Ardent\Ardent;

class Payment extends Ardent {

	protected $table = 'payments';

	public function toArray()
  	{
	    $values = parent::toArray();
	    if ( ! is_array($values['response'])) {
	      $values['response'] = unserialize($values['response']);
	    }
	    return $values;
  	}

}