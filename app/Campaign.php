<?php

namespace App;

use App\Presenters\CampaignPresenter;
use App\User;
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

    public function attachments()
    {
        return $this->belongsToMany('App\Attachment')->withTimestamps();
    }

    public function collaborators()
    {
        return $this->belongsToMany('App\User')->withTimestamps();
    }

    public function contents()
    {
        return $this->hasMany('App\Content');
    }

    public function contentsWritten()
    {
        return $this->hasMany('App\Content')->where('written', '1');
    }

    public function contentsReady()
    {
        return $this->hasMany('App\Content')->where('ready_published', '1');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function tasks()
    {
        return $this->belongsToMany('App\Task')->withTimestamps();
    }

    public function __toString()
    {
        return $this->title;
    }

    public function hasCollaborator(User $user)
    {
        $isNewCampaign = !$this->id;

        if ($isNewCampaign) {
            return true;
        }

        return (boolean) $this->collaborators()
            ->where('users.id', $user->id)
            ->count();
    }
}
