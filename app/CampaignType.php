<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class CampaignType extends Model {
	    // sure we can add variables to make it more dynamic
    //  -- 
    public static function dropdown()
    {
    	// - Create Content Type Drop Down Data
	$contenttypedd = ['' => '-- Select Campaign Type --'];
	$contenttypedd += CampaignType::select('id','name')->orderBy('name', 'asc')->distinct()->lists('name', 'id')->toArray();
	return $contenttypedd;

    }
}