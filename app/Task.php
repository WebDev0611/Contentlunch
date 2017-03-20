<?php

namespace App;

use App\Account;
use App\Presenters\TaskPresenter;
use App\User;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use Laracasts\Presenter\PresentableTrait;

class Task extends Model
{
    use PresentableTrait;

    protected $presenter = TaskPresenter::class;

    public $fillable = [
        'name',
        'url',
        'explanation',
        'start_date',
        'due_date',
        'user_id',
        'account_id',
        'status',
    ];

    public static function boot()
    {
        parent::boot();
        static::updating(function($task) {
            $task->logChanges();
        });
    }

    public function logChanges($userId = null)
    {
        $userId = $userId ?: Auth::id();
        $changed  = $this->getDirty();
        $fresh = $this->fresh()->toArray();

        array_forget($changed, ['updated_at' ]);
        array_forget($fresh, ['updated_at' ]);

        if (count($changed) > 0) {
            $this->adjustments()->create([
                'user_id' => $userId,
                'before' => json_encode(array_intersect_key($fresh, $changed)),
                'after' => json_encode($changed)
            ]);
        }
    }

    public function account()
    {
        return $this->belongsTo('App\Account');
    }

    public function calendar()
    {
        return $this->belongsTo('App\Calendar');
    }

    public function adjustments()
    {
        return $this->hasMany('App\TaskAdjustment');
    }

    public function assignedUsers()
    {
        return $this->belongsToMany('App\User');
    }

    public function attachments()
    {
        return $this->hasMany('App\Attachment');
    }

    public function campaigns()
    {
        return $this->belongsToMany('App\Campaign');
    }

    public function contents()
    {
        return $this->belongsToMany('App\Content');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function users()
    {
        return $this->account->users();
    }

    public static function search($term, $account = null)
    {
        if (!$account) {
            $account = Account::selectedAccount();
        }

        return $account
            ->tasks()
            ->where(function($q) use ($term) {
                $q->orWhere('name', 'like', '%' . $term . '%')
                  ->orWhere('explanation', 'like', '%' . $term . '%');
            })
            ->get();
    }

    public function isAssignedTo(User $user)
    {
        return (boolean) $this->assignedUsers()
            ->where('users.id', $user->id)
            ->count();
    }

    public function canBeDeletedBy(User $user)
    {
        return ($this->user_id === $user->id || $this->hasAssignedUser($user));
    }

    public function canBeEditedBy(User $user)
    {
        return ($this->user_id === $user->id || $this->hasAssignedUser($user));
    }

    public function hasAssignedUser(User $user)
    {
        return (boolean) $this->assignedUsers()
            ->where('users.id', $user->id)
            ->count();
    }

    public function assignUsers(array $userIds)
    {
        $newUsers = $this->newUsers($userIds);

        $this->assignedUsers()->sync($userIds);

        $newUsers->each(function($user) {
            $this->sendAssignedEmails($user);
        });
    }

    protected function newUsers($userIds)
    {
        $currentAssignedUsers = $this->assignedUsers()->get()->map(function($user) {
            return $user->id;
        });

        $newUsers = collect($userIds)
            ->filter(function($userId) use ($currentAssignedUsers) {
                return $currentAssignedUsers->search($userId) === false;
            })
            ->map(function($userId) {
                return User::find($userId);
            });

        return $newUsers;
    }

    protected function sendAssignedEmails($user)
    {
        Mail::send('emails.task_assignment', [ 'task' => $this ], function($message) use ($user) {
            $message->from("no-reply@contentlaunch.com", "Content Launch")
                ->to($user->email)
                ->subject('Task assigned to you: ' . $this->name);
        });
    }

    public function close()
    {
        $this->update([ 'status' => 'closed' ]);
    }

    public static function accountTasks(Account $account)
    {
        return $account
            ->tasks()
            ->with('user')
            ->with('assignedUsers')
            ->with('contents')
            ->orderBy('created_at', 'desc')
            ->where('status', 'open')
            ->get()
            ->map(function($task) {
                $task->addDueDateDiffs();
                return $task;
            });
    }

    public static function userTasks(User $user, Account $account = null)
    {
        return $user
            ->assignedTasks()
            ->when($account, function($query) use ($account) {
                return $query->where('account_id', $account->id);
            })
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->where('status', 'open')
            ->distinct()
            ->get()
            ->map(function($task) {
                $task->addDueDateDiffs();
                return $task;
            });
    }

    public static function calendarTasks(Calendar $calendar)
    {
        return $calendar
            ->tasks()
            ->with('user')
            ->with('assignedUsers')
            ->with('contents')
            ->orderBy('created_at', 'desc')
            ->where('status', 'open')
            ->get()
            ->map(function($task) {
                $task->addDueDateDiffs();
                return $task;
            });
    }

    protected function addDueDateDiffs()
    {
        $this->due_date_diff = $this->present()->dueDate;
        $this->updated_at_diff = $this->present()->updatedAt;
        $this->created_at_diff = $this->present()->createdAt;
    }

    public function statusAdjustments()
    {
        return $this->adjustments()
            ->get()
            ->filter(function($adjustment) {
                return $adjustment->hasKey('status');
            });
    }

    // $resource can be any model with a many to many relationship
    // with tasks
    public static function resourceTasks($resource, $openTasks = false)
    {
        $tasks = $resource->tasks();

        if ($openTasks) {
            $tasks = $tasks->where('status', '=', 'open');
        }

        return $tasks
            ->with('user')
            ->with('assignedUsers')
            ->get()
            ->map(function($task) {
                $task->due_date_diff = $task->present()->dueDate;
                $task->user_profile_image = $task->user ?
                    $task->user->present()->profile_image :
                    User::DEFAULT_PROFILE_IMAGE;

                return $task;
            });
    }
}
