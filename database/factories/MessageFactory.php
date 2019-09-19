<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Message;
use App\User;
use Faker\Generator as Faker;

$factory->define(Message::class, function (Faker $faker) {
    return [
        'content' => $faker->text,
        'receiver_id' => factory(User::class),
        'sender_id' => factory(User::class)
    ];
});
