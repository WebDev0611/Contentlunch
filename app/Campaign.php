<?php

namespace App;

use App\Presenters\CampaignPresenter;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Laracasts\Presenter\PresentableTrait;

class Campaign extends Model
{
    use PresentableTrait;

    protected $presenter = CampaignPresenter::class;

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

    public function account()
    {
        return $this->belongsTo('App\Account');
    }

    public function collaborators()
    {
        return $this->belongsToMany('App\User');
    }

    public function contents()
    {
        return $this->hasMany('App\Content');
    }

    public function creator()
    {
        return $this->belongsTo('App\User');
    }

    public function __toString()
    {
        return $this->title;
    }
}
