<?php namespace App;

use App\Account;
use App\Helpers;
use App\Presenters\ContentPresenter;
use App\Traits\ConfiguresContentActions;
use App\Traits\Orderable;
use Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Laracasts\Presenter\PresentableTrait;
use Spatie\Activitylog\Traits\LogsActivity;
use App\Traits\HandlesContentHistory;

class Content extends Model
{
    use PresentableTrait, Orderable, LogsActivity, ConfiguresContentActions, HandlesContentHistory;

    public $presenter = ContentPresenter::class;

    protected static $logAttributes = [
        'content_type_id',
        'account_id',
        'due_date',
        'title',
        'connection_id',
        'body',
        'buying_stage_id',
        'persona_id',
        'campaign_id',
        'meta_title',
        'meta_keywords',
        'meta_description',
        'content_status_id',
        'archived',
        'ready_published',
        'published',
        'written',
        'user_id',
        'email_subject',
        'calendar_id',
        'mailchimp_settings',
    ];

    protected static $logOnlyDirty = true;

    public $fillable = [
        'title',
        'body',
        'content_type_id',
    ];

    public function account()
    {
        return $this->belongsTo('App\Account');
    }

    public function adjustments()
    {
        return $this->belongsToMany('App\User', 'adjustments')
            ->withTimestamps()
            ->withPivot(['before', 'after','id'])
            ->latest('pivot_updated_at');
    }

    public function attachments()
    {
        return $this->hasMany('App\Attachment');
    }

    public function authors()
    {
        return $this->belongsToMany('App\User');
    }

    public function buying_stage()
    {
        return $this->belongsTo('App\BuyingStage');
    }

    public function calendar()
    {
        return $this->belongsTo('App\Calendar');
    }

    public function campaign()
    {
        return $this->belongsTo('App\Campaign');
    }

    public function collaborators()
    {
        return $this->belongsToMany('App\User');
    }

    public function connection()
    {
        return $this->belongsTo('App\Connection');
    }

    public function contentType()
    {
        return $this->belongsTo('App\ContentType');
    }

    public function guests()
    {
        return $this->belongsToMany('App\User', 'content_guest');
    }

    public function invites()
    {
        return $this->morphMany('App\AccountInvite', 'inviteable');
    }

    public function messages()
    {
        return $this->hasMany('App\ContentMessage');
    }

    public function persona()
    {
        return $this->belongsTo('App\Persona');
    }

    public function related()
    {
       return $this->belongsToMany('App\Content', 'content_related', 'content_id', 'related_content_id');
    }

    public function status()
    {
        return $this->belongsTo('App\ContentStatus', 'content_status_id');
    }

    public function tags()
    {
        return $this->belongsToMany('App\Tag');
    }

    public function tasks()
    {
        return $this->belongsToMany('App\Task');
    }

    public function __toString()
    {
        return $this->title;
    }

    public static function search($term, $account = null)
    {
        if (!$account) {
            $account = Account::selectedAccount();
        }

        return $account
            ->contents()
            ->where(function($q) use ($term) {
                $q
                    ->orWhere('title', 'like', '%' . $term . '%')
                    ->orWhere('body', 'like', '%' . $term . '%');
            })
            ->get();
    }

    public static function calendarContents(Calendar $calendar)
    {
        $content = $calendar
            ->contents()
            ->with('authors')
            ->get();

        return $content;
    }

    public function hasCollaborator(User $user)
    {
        return (boolean) $this->authors()
            ->where('users.id', $user->id)
            ->count();
    }

    public function hasGuest(User $user)
    {
        return (boolean) $this->guests()
            ->where('users.id', $user->id)
            ->count();
    }

    public function hasAccessToMessages(User $user)
    {
        return $this->hasCollaborator($user) || $this->hasGuest($user);
    }

    public function author() {
        $author = $this->authors()->orderBy('created_at')->first();

        return $author ? $author : null;
    }

    public function dueDateDiffFromToday()
    {
        return Carbon::now()->diffInDays(new Carbon($this->due_date));
    }

    public function isDueDateCritical()
    {
        return $this->dueDateDiffFromToday() <= 2;
    }
}