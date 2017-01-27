<?php

namespace App;

use App\Account;
use App\Presenters\TaskPresenter;
use App\User;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use Laracasts\Presenter\PresentableTrait;
use Log;

class Task extends Model
{
    use PresentableTrait;

    protected $presenter = TaskPresenter::class;

    public $fillable = [
        'name',
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

    public function adjustments()
    {
        return $this->hasMany('App\TaskAdjustment');
    }

    public function attachments()
    {
        return $this->hasMany('App\Attachment');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function users()
    {
        return $this->account->users();
    }

    public function assignedUsers()
    {
        return $this->belongsToMany('App\User');
    }

    public function contents()
    {
        return $this->belongsToMany('App\Content');
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
            Log::info([ 'user' => $user->name ]);
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
                ->subject('You were assigned to the task ' . $this->name);
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
            ->get()
            ->map(function($task) {
                $task->addDueDateDiffs();
                return $task;
            });
    }

    public static function userTasks(User $user)
    {
        return $user
            ->assignedTasks()
            ->with('user')
            ->distinct()
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
}
