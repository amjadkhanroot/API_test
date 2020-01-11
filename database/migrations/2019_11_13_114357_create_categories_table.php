<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table -> unsignedBigInteger('user_id');
            // set foreign key with a reference.
            $table -> foreign('user_id')
                    -> references('id')
                    -> on('users')
                    -> onDelete('cascade');

            $table -> string('title');
            // can be null.
            $table -> text('description')->nullable();
            // a user can restore a deleted category.
            $table -> softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
