<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Message;
use App\User;
use Faker\Generator as Faker;

$factory->define(Message::class, function (Faker $faker) {
    return [
        'user_id' => factory(User::class),
        'subject' => $faker->sentence,
        'content' => $faker->text,
        'startDate' => '01/20/2020',
        'expirationDate' => '01/25/2020'
    ];
});
