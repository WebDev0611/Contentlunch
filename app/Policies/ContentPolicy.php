<?php

namespace App\Policies;

use App\Account;
use App\Content;
use App\Limit;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ContentPolicy
{
    use HandlesAuthorization;

    public function before (User $user)
    {
        if (!Account::selectedAccount()->activeSubscriptions()->isEmpty())
        {
            return true;
        }
    }

    public function launch(User $user)
    {
        $launches = $user->limits()->monthly()->whereName('content_launch')->count();
        $maxLaunches = Limit::whereName('content_launch')->first()->value;

        return $launches < $maxLaunches;
    }
}
