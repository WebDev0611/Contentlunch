<?php

$factory->define(App\BuyingStage::class, function (Faker\Generator $faker) {
    return [
        'name' => ucwords($faker->word) . ' Stage',
        'description' => $faker->realText(200),
        'account_id' => function (array $buyingStage) use ($faker) {
            $accounts = App\Account::all();

            if ($accounts->isEmpty()) {
                $account = factory(App\Account::class)->create();
            } else {
                $account = $faker->randomElement($accounts);
            }

            return $account->id;
        }
    ];
});
