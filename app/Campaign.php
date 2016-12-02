<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    public $fillable = [
        'user_id',
        'account_id',
        'title',
        'status',
        'campaign_type_id',
        'start_date',
        'end_date',
        'is_recurring',
        'description',
        'goals',
    ];

    public function contents()
    {
        return $this->hasMany('App\Content');
    }

    public function account()
    {
        return $this->belongsTo('App\Account');
    }

    // - Eek not sure if this make sense to pull user specific drop down from compaign model
    // -- maybe from user model with different function name
    public static function dropdown($user = null)
    {
        $user = $user ?: Auth::user();
        // - Create Campaign Drop Down Data
        $campaignDropdown = ['' => '-- Select a Campaign --'];
        $campaignDropdown += $user->campaigns()
            ->select('id', 'title')
            ->where('status', 1)
            ->orderBy('title', 'asc')
            ->distinct()
            ->lists('title', 'id')
            ->toArray();

        return $campaignDropdown;
    }

    public function __toString()
    {
        return $this->title;
    }
}
