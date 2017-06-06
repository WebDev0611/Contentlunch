<?php

namespace App;

use App\Presenters\CampaignPresenter;
use App\User;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Laracasts\Presenter\PresentableTrait;
use Spatie\Activitylog\Traits\LogsActivity;

class Campaign extends Model
{
    use PresentableTrait, LogsActivity;

    protected $presenter = CampaignPresenter::class;

    const INACTIVE = 0;
    const ACTIVE = 1;
    const PAUSED = 2;

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
        'interval'
    ];

    protected static $logAttributes = [
        'title',
        'status',
        'campaign_type_id',
        'start_date',
        'end_date',
        'is_recurring',
        'description',
        'goals',
    ];

    protected static $logOnlyDirty = true;

    public function account()
    {
        return $this->belongsTo('App\Account');
    }

    public function attachments()
    {
        return $this->belongsToMany('App\Attachment')->withTimestamps();
    }

    public function guests()
    {
        return $this->belongsToMany('App\User', 'campaign_guest');
    }

    public function invites()
    {
        return $this->morphMany('App\AccountInvite', 'inviteable');
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
        return $this->hasMany('App\Content')->where('content_status_id', ContentStatus::BEING_WRITTEN);
    }

    public function contentsReady()
    {
        return $this->hasMany('App\Content')->where('content_status_id', ContentStatus::READY);
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

    public function scopeActive($query)
    {
        return $query->where('status', static::ACTIVE);
    }

    public function scopeInactive($query)
    {
        return $query->where('status', static::INACTIVE);
    }

    public function scopePaused($query)
    {
        return $query->where('status', static::PAUSED);
    }

    public function activate()
    {
        return $this->update([ 'status' => static::ACTIVE ]);
    }

    public function isActive()
    {
        return $this->status == static::ACTIVE;
    }

    public function deactivate()
    {
        return $this->update([ 'status' => static::INACTIVE ]);
    }

    public function isInactive()
    {
        return $this->status == static::INACTIVE;
    }

    public function pause()
    {
        return $this->update([ 'status' => static::PAUSED ]);
    }

    public function isPaused()
    {
        return $this->status == static::PAUSED;
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

    public function availableContents()
    {
        return Account::selectedAccount()
            ->contents()
            ->where('campaign_id', null)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public static function search($term, $account = null)
    {
        if (!$account) {
            $account = Account::selectedAccount();
        }

        return $account
            ->campaigns()
            ->where(function($q) use ($term) {
                $q->orWhere('title', 'like', '%' . $term . '%')
                    ->orWhere('description', 'like', '%' . $term . '%');
            })
            ->get();
    }
}
