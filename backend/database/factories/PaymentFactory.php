<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\Payment;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Payment::class, function (Faker $faker) {
    return [
        'apartment_id' => rand(1,5),
        'tenant_id' => rand(1,5),
        'value' => rand(800,1000),
        'due_at' => $faker->dateTimeBetween('-6 months', '+6 months', null),
        'payed' => false,
    ];
});
