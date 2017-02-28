<?php

namespace App\Policies;

use App\Account;

class BasePolicy
{
    protected $account;

    function __construct()
    {
        $this->account = Account::selectedAccount();
    }
}