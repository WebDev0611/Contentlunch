<?php

namespace App\Policies;

use App\Content;
use App\Limit;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ContentPolicy extends BasePolicy
{
    use HandlesAuthorization;

    public function launch(User $user)
    {
        if (!$this->account->hasLimit('content_launch')) {
            return true;
        }

        $launches = $user->limits()->monthly()->whereName('content_launch')->count();
        $maxLaunches = $this->account->limit('content_launch');

        return $launches < $maxLaunches;
    }
}
