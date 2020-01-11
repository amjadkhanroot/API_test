<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Task;
use Faker\Generator as Faker;

$factory->define(Task::class, function (Faker $faker) {
    return [
        'user_id'=> \App\User::all()->random()->id,
        'title'=> $faker->country,
        'description'=> $faker->city,
        'due_date'=> $faker->date('Y-m-d')
    ];
});
