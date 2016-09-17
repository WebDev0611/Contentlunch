<?php 
namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model {

    public function contents()
    {
       return $this->hasMany('App\Content');
    }

    // - Eek not sure if this make sense to pull user specific drop down from compaign model
    // -- maybe from user model with different function name
    public static function dropdown($user = null)
    {
    	$user = $user ?: Auth::user();
	// - Create Campaign Drop Down Data
	$campaigndd = ['' => '-- Select a Campaign --'];
	$campaigndd += $user->campaigns()->select('id','title')->where('status',1)->orderBy('title', 'asc')->distinct()->lists('title', 'id')->toArray();
	return $campaigndd;

    }
}