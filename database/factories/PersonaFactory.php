<?php

$factory->define(App\Persona::class, function (Faker\Generator $faker) {
    return [
        'name' => ucwords($faker->word),
        'description' => $faker->realText(200),
        'account_id' => function (array $buyingStage) {
            return factory(App\Account::class)->create()->id;
        }
    ];
});
