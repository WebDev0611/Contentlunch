<?php

namespace App\Policies;

use App\Account;

class BasePolicy
{
    public $account;

    function __construct()
    {
        $this->account = Account::selectedAccount();
    }
}