<?php

namespace App;

use App\Account;
use App\Presenters\TaskPresenter;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Laracasts\Presenter\PresentableTrait;

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

    public function attachments()
    {
        return $this->hasMany('App\Attachment');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
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
}
