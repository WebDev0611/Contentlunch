<?php

$factory->define(App\Message::class, function (Faker\Generator $faker) {
    return [
        'sender_id' => function($task) use ($faker) {
            $user = factory(App\User::class)->create();

            return $user->id;
        },

        'recipient_id' => function($task) use ($faker) {
            $user = factory(App\User::class)->create();

            return $user->id;
        },

        'body' => $faker->realText,
        'read' => $faker->boolean,

        'created_at' => $faker->dateTimeBetween('-1 week', 'now'),
        'updated_at' => function($task) {
            return $task['created_at'];
        }
    ];
});