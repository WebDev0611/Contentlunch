<?php

$factory->define(App\Account::class, function (Faker\Generator $faker) {
    return [
        'name' => ucwords($faker->word) . ' Account',
        'account_type_id' => function () use ($faker) {
            $accountTypes = App\AccountType::all();

            if ($accountTypes->isEmpty()) {
                return factory(App\AccountType::class)->create()->id;
            } else {
                return $accountTypes[0]->id;
            }
        },
        'parent_account_id' => null
    ];
});

$factory->defineAs(App\Account::class, 'agency', function () use ($factory) {
    $account = $factory->raw(App\Account::class);

    return array_merge($account, ['account_type_id' => \App\AccountType::AGENCY]);
});

$factory->defineAs(App\Account::class, 'company', function () use ($factory) {
    $account = $factory->raw(App\Account::class);

    return array_merge($account, ['account_type_id' => \App\AccountType::COMPANY]);
});

$factory->define(App\AccountType::class, function (Faker\Generator $faker) {
    return [
        'name' => ucwords($faker->word) . ' Type',
    ];
});