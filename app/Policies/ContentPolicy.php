<?php

namespace App\Policies;

use App\Content;
use App\Limit;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ContentPolicy
{
    use HandlesAuthorization;

    public function launch(User $user)
    {
        $launches = $user->limits()->monthly()->whereName('content_launch')->count();
        $maxLaunches = Limit::whereName('content_launch')->first()->value;

        return $launches < $maxLaunches;
    }
}
