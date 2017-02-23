<?php

namespace App\Policies;

use App\Idea;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class IdeaPolicy
{
    use HandlesAuthorization;

    public function search(User $user)
    {
        return true;
    }
}
