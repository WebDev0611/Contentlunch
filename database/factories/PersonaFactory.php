<?php

$factory->define(App\Persona::class, function (Faker\Generator $faker) {
    return [
        'name' => ucwords($faker->word),
        'description' => $faker->realText(200),
        'account_id' => function (array $buyingStage) {
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
