<?php

namespace App\Policies;

use App\Account;
use App\Limit;
use App\SubscriptionType;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AccountPolicy extends BasePolicy
{
    use HandlesAuthorization;

    public function invite(User $user, Account $account, array $emails)
    {
        if (!$account->hasLimit('users_per_account')) {
            return true;
        }

        $currentUsersCount = $account->users()->count();
        $emailsCount = collect($emails)->count();

        $maxUsersCount = $account->limit('users_per_account');

        return ($emailsCount + $currentUsersCount) <= $maxUsersCount;
    }

    public function createSubaccount(User $user, Account $account)
    {
        if (!$account->hasLimit('subaccounts_per_account')) {
            return $account->isAgencyAccount();
        }

        $currentSubaccounts = $account->childAccounts()->count();
        $maxSubaccounts = $account->limit('subaccounts_per_account');

        return ($currentSubaccounts < $maxSubaccounts) && $account->isAgencyAccount();
    }
}
