<?php

use App\User;
use App\Models\Messages;
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

$factory->define(App\Models\Messages::class, function (Faker $faker) {
    $user = User::all()->random();
    return [
        'user_id' =>  $user->id,
        'parent_id' => 0,
        'status_id' => rand(1, 3),
        'message'   => $faker->realText(rand(200,1000)),
        'created_at' => $faker->dateTimeBetween('-3 months','-2 months')

    ];
});
