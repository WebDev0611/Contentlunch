<?php

namespace App\Policies;

use App\Account;
use App\Limit;
use App\SubscriptionType;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AccountPolicy
{
    use HandlesAuthorization;

    public function invite(User $user, Account $account, array $emails)
    {
        $currentUsersCount = $account->users()->count();
        $emailsCount = collect($emails)->count();

        if (!Account::selectedAccount()->activeSubscriptions()->isEmpty())
        {
            $slug = $account->activeSubscriptions()[0]->subscriptionType->slug;
            $maxUsersCount = SubscriptionType::whereSlug($slug)->first()->limit_users;
        } else {
            $maxUsersCount = Limit::whereName('users_per_account')->first()->value;
        }

        return ($emailsCount + $currentUsersCount) < $maxUsersCount;
    }

    public function createSubaccount(User $user, Account $account)
    {
        if (!Account::selectedAccount()->activeSubscriptions()->isEmpty())
        {
            return true;
        }

        $currentSubaccounts = $account->childAccounts()->count();
        $maxSubaccounts = Limit::whereName('subaccounts_per_account')->first()->value;

        return ($currentSubaccounts < $maxSubaccounts) && $account->isAgencyAccount();
    }
}
