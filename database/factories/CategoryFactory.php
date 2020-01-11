<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Category;
use Faker\Generator as Faker;

$factory->define(Category::class, function (Faker $faker) {
    return [

        'user_id'=> \App\User::all()->random()->id,
        'title'=> 'sentence',
        'description'=> 'dd'
    ];
});
