<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // using Factory Category --> task
        factory(\App\Category::class, 10)->create()->each(function ($category){
            $category->tasks()->saveMany(factory(\App\Task::class, 10)->make());
        });

        // Without Factory
//        DB::table('categories')->insert([
//            [
//                'user_id'=> \App\User::all()->random()->id,
//                'title'=> Str::random(5),
//                'description'=> Str::random(5),
//
//            ],
//            [
//                'user_id'=> \App\User::all()->random()->id,
//                'title'=> Str::random(5),
//                'description'=> Str::random(5),
//
//            ],
//            [
//                'user_id'=> \App\User::all()->random()->id,
//                'title'=> Str::random(5),
//                'description'=> Str::random(5),
//
//            ],
//            [
//                'user_id'=> \App\User::all()->random()->id,
//                'title'=> Str::random(5),
//                'description'=> Str::random(5),
//
//            ]
//        ]);
    }
}
