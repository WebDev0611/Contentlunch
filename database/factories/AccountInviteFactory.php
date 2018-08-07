<?php

use App\Account;
use App\AccountInvite;
use App\AccountType;

$factory->define(AccountInvite::class, function (Faker\Generator $faker) {
    return [
        'email' => $faker->email,
        'account_id' => function() {
            return factory(Account::class)->create()->id;
        }
    ];
});

$factory->defineAs(AccountInvite::class, 'guest', function () use ($factory) {
    $account = $factory->raw(AccountInvite::class);

    return array_merge($account, ['is_guest' => true]);
});