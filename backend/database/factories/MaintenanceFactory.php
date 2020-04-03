<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\Maintenance;
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

$factory->define(Maintenance::class, function (Faker $faker) {
    return [
        'tenant_id' => rand(1,5),
        'apartment_id' => rand(1,5),
        'maintenance_user_id' => null,
        'problem' => $faker->paragraph(3, true),
        'solution' => null,
        'solved' => false,
    ];
});
