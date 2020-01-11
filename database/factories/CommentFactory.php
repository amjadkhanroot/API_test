<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Comment;
use Faker\Generator as Faker;

$factory->define(Comment::class, function (Faker $faker) {
    return [
        'content'=> $faker->domainName,
        'user_id'=> \App\User::all()->random()->id,
        'task_id'=> \App\Task::all()->random()->id
    ];
});
