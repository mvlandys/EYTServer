<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMrantGamesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mrant_games', function (Blueprint $table)
        {
            $table->increments('id');
            $table->timestamps();
            $table->string("subject_id");
            $table->string("session_id")->nullable();
            $table->string("test_name")->nullable();
            $table->string("grade")->nullable();
            $table->date("dob")->nullable();
            $table->integer("age")->nullable();
            $table->string("sex")->nullable();
            $table->dateTime("played_at")->nullable();
            $table->string("score")->nullable();
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('mrant_games');
    }
}
