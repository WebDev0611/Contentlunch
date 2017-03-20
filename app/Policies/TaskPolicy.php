<?php

namespace App\Policies;

use App\Task;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaskPolicy
{
    use HandlesAuthorization;

    public function destroy(User $user, Task $task)
    {
        return $task->user_id === $user->id || $task->hasAssignedUser($user);
    }

    public function update(User $user, Task $task)
    {
        return $task->user_id === $user->id || $task->hasAssignedUser($user);
    }

    public function show(User $user, Task $task)
    {
        return $task->account->hasUser($user);
    }
}
