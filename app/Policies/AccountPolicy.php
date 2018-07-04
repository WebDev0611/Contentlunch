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

    public function join(User $user, Account $account)
    {
        if (!$account->hasLimit('users_per_account')) {
            return true;
        }

        $currentUsers = $this->usersCount($account);
        $maxUsersCount = $account->limit('users_per_account');

        return $currentUsers < $maxUsersCount;
    }

    protected function usersCount($account)
    {
        return $account->users()->where('is_guest', false)->count();
    }

    public function invite(User $user, Account $account, array $emails)
    {
        if (!$account->hasLimit('users_per_account')) {
            return true;
        }

        $currentUsersCount = $this->usersCount($account);
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
