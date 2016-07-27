<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class BuyingStage extends Model {

	public static function dropdown() 
	{

		// - Create Related Content Drop Down Data
		$stageddd = ['' => '-- Select a Buying Stage --'];
		$stageddd += BuyingStage::select('id','name')->orderBy('name', 'asc')->distinct()->lists('name', 'id')->toArray();
		return $stageddd;
	}

}