<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TaskTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // Task --> comment
        factory(\App\Task::class, 10)->create()->each(function ($task){
            $task->comments()->saveMany(factory(\App\Comment::class, 10)->make());
        });


//        DB::table('tasks')->insert([
//            [
//                'user_id'=> \App\User::all()->random()->id,
//                'category_id'=> \App\Category::all()->random()->id,
//                'title'=> Str::random(5),
//                'description'=> Str::random(5),
//                'due_date'=> '2020-11-11'
//            ],
//            [
//                'user_id'=> \App\User::all()->random()->id,
//                'category_id'=> \App\Category::all()->random()->id,
//                'title'=> Str::random(5),
//                'description'=> Str::random(5),
//                'due_date'=> '2020-11-11'
//            ],
//            [
//                'user_id'=> \App\User::all()->random()->id,
//                'category_id'=> \App\Category::all()->random()->id,
//                'title'=> Str::random(5),
//                'description'=> Str::random(5),
//                'due_date'=> '2020-11-11'
//            ],
//            [
//                'user_id'=> \App\User::all()->random()->id,
//                'category_id'=> \App\Category::all()->random()->id,
//                'title'=> Str::random(5),
//                'description'=> Str::random(5),
//                'due_date'=> '2020-11-11'
//            ]
//        ]);
    }
}
