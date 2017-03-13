<?php

namespace App\Presenters;

use App\Account;
use App\Presenters\Helpers\BasePresenter;

class AccountPresenter extends BasePresenter
{
    public function account_image()
    {
        return $this->entity->account_image ?
            $this->entity->account_image :
            Account::DEFAULT_ACCOUNT_IMAGE;
    }

    public function tagsDropdown()
    {
        return $this->entity
            ->tags()
            ->select('id', 'tag')
            ->orderBy('tag', 'asc')
            ->distinct()
            ->lists('tag', 'id')
            ->toArray();
    }

    public function tagsJson()
    {
        return $this->entity
            ->tags()
            ->select('tag')
            ->orderBy('tag', 'asc')
            ->distinct()
            ->lists('tag')
            ->toJson();
    }

    public function usersCountStatus()
    {
        $usersCount = $this->entity->users->count();
        $limit = $this->entity->limit('users_per_account');

        return "{$usersCount}/{$limit}";
    }

    public function subAccountsStatus()
    {
        $childAccounts = $this->entity->proxyToParent()->childAccounts->count();
        $limit = $this->entity->limit('subaccounts_per_account');

        return "{$childAccounts}/{$limit}";
    }
}
