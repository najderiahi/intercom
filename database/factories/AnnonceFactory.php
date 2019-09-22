<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Annonce;
use App\User;
use Faker\Generator as Faker;

$factory->define(Annonce::class, function (Faker $faker) {
    return [
        'content' => $faker->text,
        'user_id' => factory(User::class)
    ];
});
