<?php namespace App;

use App\Account;
use App\Helpers;
use App\Presenters\ContentPresenter;
use App\Traits\Orderable;
use Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Laracasts\Presenter\PresentableTrait;
use Spatie\Activitylog\Traits\LogsActivity;

class Content extends Model
{
    use PresentableTrait, Orderable, LogsActivity;

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

    /**
     * Human readable column names.
     *
     * @var array
     */
    private static $fieldNames = [
        'id'               => 'ID',
        'content_type_id'  => 'Content Type',
        'account_id'       => 'Account',
        'due_date'         => 'Due Date',
        'title'            => 'Title',
        'connection_id'    => 'Connection',
        'body'             => 'Body',
        'buying_stage_id'  => 'Buying Stage',
        'persona_id'       => 'Persona',
        'campaign_id'      => 'Campaign',
        'meta_title'       => 'Meta Title',
        'meta_keywords'    => 'Meta Keywords',
        'meta_description' => 'Meta Description',
        'content_status_id' => 'Content Status',
        'archived'         => 'Archived',
        'ready_published'  => 'Ready to be published',
        'published'        => 'Published',
        'written'          => 'Written',
        'user_id'          => 'Author',
        'email_subject'    => 'Email Subject',
        'calendar_id'      => 'Calendar',
        'created_at'       => 'Create at',
        'updated_at'       => 'Updated at',
        'mailchimp_settings'       => 'Mailchimp Settings'
    ];

    public $fillable = [
        'title',
        'body',
        'content_type_id',
    ];

    public static function fieldName($key = null)
    {
        return Content::$fieldNames[$key];
    }

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

    /**
     * Configures the flags ready_published, written and published according to the
     * $action string
     */
    public function configureAction($action = null)
    {
        $status = ContentStatus::BEING_WRITTEN;

        switch ($action) {
            case 'ready_to_publish': $status = ContentStatus::READY; break;
            case 'publish': $status = ContentStatus::PUBLISHED; break;
            case 'archived': $status = ContentStatus::ARCHIVED; break;
            default: break;
        }

        $this->status()->associate($status)->save();
    }

    public function scopeReadyToPublish($query)
    {
        return $query->where('content_status_id', ContentStatus::READY);
    }

    public function scopeWritten($query)
    {
        return $query->where('content_status_id', ContentStatus::BEING_WRITTEN);
    }

    public function scopePublished($query)
    {
        return $query->where('content_status_id', ContentStatus::PUBLISHED);
    }

    public function scopeArchived($query)
    {
        return $query->where('content_status_id', ContentStatus::ARCHIVED);
    }

    public function scopeCurrent ($query)
    {
        return $query->whereIn('content_status_id', [ContentStatus::READY, ContentStatus::BEING_WRITTEN]);
    }

    public function setReadyPublished()
    {
        $this->configureAction('ready_to_publish');
    }

    public function setWritten()
    {
        $this->configureAction('written_content');
    }

    public function setPublished()
    {
        $this->configureAction('publish');
    }

    public function setArchived()
    {
        $this->configureAction('archived');
    }

    /**
     * Returns a clean value to be used in the content history sidebar.
     *
     * @param  string $key
     * @param  string $value
     * @return string
     */
    public static function cleanedHistoryContent($key, $value)
    {
        switch ($key) {
            case 'content_type_id':
            case 'connection_id':
            case 'buying_stage_id':
            case 'campaign_id':
            case 'user_id':
                $formattedContent = Helpers::getRelatedContentString($key, $value);
                break;

            case 'body':
                $formattedContent = $value ? strip_tags($value) : '-';
                break;

            case 'due_date':
                $formattedContent = $value == '0000-00-00' ? 'Empty Date' : $value;
                break;

            case 'archived':
            case 'ready_published':
            case 'published':
            case 'written':
                $formattedContent = $value ? 'Yes' : 'No';
                break;

            case 'content_status_id':
                $formattedContent = $value ? ContentStatus::find($value)->name : '-';
                break;

            case 'mailchimp_settings':
                $formattedContent = '-';
                break;

            default:
                $formattedContent = $value ? $value : '-';
                break;
        }

        return $formattedContent;
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

    public function history()
    {
        return $this->contentTasksHistory()
            ->merge($this->contentAdjustments())
            ->sort(function($adjustmentA, $adjustmentB) {
                return $adjustmentA['date']->lt($adjustmentB['date']) ? 1 : -1;
            });
    }

    protected function contentAdjustments()
    {
        return $this->adjustments
            ->map(function($adjustmentUser) {
                return [
                    'type' => 'content',
                    'adjustment' => $adjustmentUser,
                    'date' => $adjustmentUser->pivot->created_at,
                ];
            });
    }

    protected function contentTasksHistory()
    {
        return $this->tasks
            ->map(function($task) { return $task->statusAdjustments(); })
            ->flatten(1)
            ->map(function($taskAdjustment) {
                return [
                    'type' => 'content_task',
                    'adjustment' => $taskAdjustment,
                    'date' => $taskAdjustment->created_at,
                ];
            });
    }
}