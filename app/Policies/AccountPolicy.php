<?php

namespace App\Policies;

use App\Account;
use App\Limit;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AccountPolicy
{
    use HandlesAuthorization;

    public function invite(User $user, array $emails)
    {
        $currentUsersCount = Account::selectedAccount()->users()->count();
        $emailsCount = collect($emails)->count();
        $maxUsersCount = Limit::whereName('users_per_account')->first()->value;

        return ($emailsCount + $currentUsersCount) < $maxUsersCount;
    }
}
