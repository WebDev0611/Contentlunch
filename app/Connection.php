<?php namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;

use App\Account;

class Connection extends Model {

    protected $hidden = [ 'created_at', 'updated_at' ];
    protected $fillable = ['name', 'provider_id', 'active', 'user_id', 'settings'];


    public function provider()
    {
        return $this->belongsTo('App\Provider', 'provider_id');
    }

    public function contents()
    {
       return $this->hasMany('App\Content');
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeSuccesful($query)
    {
        return $query->where('successful', true);
    }

    // - A Getter for settings so we can decode the JSON
    public function getSettings()
    {
        return json_decode($this->settings ? $this->settings : "{}");
    }

    public function saveSettings(Array $elements)
    {
        $settings = $this->getSettings();

        foreach ($elements as $key => $value) {
            $settings->$key = $value;
        }

        $this->settings = json_encode($settings);
        $this->save();

        return $settings;
    }

    public static function getConnectionbySlug($slug)
    {
        $result = Auth::user()
            ->connections()
            ->join('providers','.providers.id', '=', 'connections.provider_id')
            ->where('slug',$slug)
            ->select('settings')
            ->first();

        // - return a value
        if (count($result) > 0) {
            $key = $result->toArray();
            return json_decode($key['settings']);
        }
        return [];
    }

    public static function dropdown()
    {
        // - Create Connections Drop Down Data
        $connectionsdd = ['' => '-- Select Destination --'];
        $connectionsdd += Account::selectedAccount()->connections()
            ->select('id','name')
            ->where('active',1)
            ->orderBy('name', 'asc')
            ->distinct()
            ->lists('name', 'id')
            ->toArray();

        return $connectionsdd;
    }

    public function __toString()
    {
        return $this->name;
    }

    public function belongsToAccount(\App\Account $account)
    {
        return $this->account_id == $account->id;
    }
}