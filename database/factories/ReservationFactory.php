<?php

use App\Reservation;
use Illuminate\Database\Eloquent\Factory;
use Faker\Generator as Faker;

/** @var Factory $factory */
$factory->define(Reservation::class, function (Faker $faker) {
    return [
        'email' => $faker->email,
        'status_id' => Reservation::RESERVED
    ];
});
