<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Policy;
use Faker\Generator as Faker;

$factory->define(Policy::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence(rand(2, 4)),
        'date' => $faker->date(),
        'name_on_policy' => $faker->name,
        'version' => $faker->numberBetween(1,3),
        'description' => $faker->randomHtml(),
    ];
});
