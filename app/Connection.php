<?php namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;

class Connection extends Model {

    protected $hidden = [ 'provider_id', 'user_id', 'created_at', 'updated_at'];
    protected $fillable = ['name', 'provider_id', 'active', 'user_id', 'settings'];


    public function provider()
    {
        return $this->belongsTo('App\Provider', 'provider_id');
    }

    public function contents()
    {
       return $this->hasMany('App\Content');
    }

    // - A Getter for settings so we can decode the JSON
    public function getSettings()
    {
        return json_decode($this->settings);
    }

    public static function  getConnectionbySlug($slug)
    {
        $result = Auth::user()->connections()->join('providers','.providers.id', '=', 'connections.provider_id')->where('slug',$slug)->select('settings')->first();
        // - return a value
        if(count($result) > 0 ) {
            $key = $result->toArray();
            return json_decode($key['settings']);
        }
        return [];
    }

    public static function dropdown()
    {
            // - Create Connections Drop Down Data
        $connectionsdd = ['' => '-- Select Destination --'];
        $connectionsdd += Connection::select('id','name')->where('active',1)->orderBy('name', 'asc')->distinct()->lists('name', 'id')->toArray();
        return $connectionsdd;
    }

}