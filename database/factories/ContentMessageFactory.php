<?php

$factory->define(App\ContentMessage::class, function (Faker\Generator $faker) {
    return [
        'sender_id' => function($task) use ($faker) {
            $user = factory(App\User::class)->create();

            return $user->id;
        },

        'content_id' => function($task) use ($faker) {
            $content = factory(App\Content::class)->create(['user_id' => $task['user_id']]);

            return $content;
        },

        'body' => $faker->realText,

        'created_at' => $faker->dateTimeBetween('-1 week', 'now'),
        'updated_at' => function($task) {
            return $task['created_at'];
        }
    ];
});