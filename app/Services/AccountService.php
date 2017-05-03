<?php

namespace App\Services;

use App\Account;

class AccountService
{
    protected $account;
    protected $selectedAccount;

    function __construct(Account $account)
    {
        $this->account = $account;
        $this->selectedAccount = Account::selectedAccount() ?: $account;
    }

    public function collaborators()
    {
        return $this->selectedAccount->proxyToParent()
            ->users()
            ->get();
    }

    public function formattedCollaborators()
    {
        return $this->collaborators()
            ->map(function($user) {
                $user->profile_image = $user->present()->profile_image;
                $user->location = $user->present()->location;
                $user->total_tasks = $user->assignedTasks()
                    ->where('account_id', $this->selectedAccount->id)
                    ->where('status', 'open')
                    ->count();

                return $user;
            });
    }
}