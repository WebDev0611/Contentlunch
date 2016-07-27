<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class ContentType extends Model {

    protected $hidden = [ 'content_type_id', 'connection_id', 'created_at', 'updated_at', 'user_id', 'campaign_id'];
    protected $fillable = ['title', 'body', 'buying_stage', 'user_id', 'settings'];


    public function contents()
    {
       return $this->hasMany('App\Content');
    }

    // sure we can add variables to make it more dynamic
    //  -- 
    public static function dropdown()
    {
    	// - Create Content Type Drop Down Data
	$contenttypedd = ['' => '-- Select Content Type --'];
	$contenttypedd += ContentType::select('id','name')->orderBy('name', 'asc')->distinct()->lists('name', 'id')->toArray();
	return $contenttypedd;

    }
    
}