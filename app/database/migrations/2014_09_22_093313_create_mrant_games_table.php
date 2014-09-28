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
            $table->string("subject_id");
            $table->string("session_id")->nullable();
            $table->string("test_name")->nullable();
            $table->string("grade")->nullable();
            $table->string("dob")->nullable();
            $table->string("age")->nullable();
            $table->string("sex")->nullable();
            $table->string("played_at")->nullable();
            $table->string("score")->nullable();
            $table->string("ts_start")->nullable();
            $table->string("ts_lvl1_start")->nullable();
            $table->string("ts_lvl1_end")->nullable();
            $table->string("ts_lvl2_start")->nullable();
            $table->string("ts_lvl2_end")->nullable();
            $table->string("ts_lvl3_start")->nullable();
            $table->string("ts_lvl3_end")->nullable();
            $table->string("ts_lvl4_start")->nullable();
            $table->string("ts_lvl4_end")->nullable();
            $table->string("ts_lvl5_start")->nullable();
            $table->string("ts_lvl5_end")->nullable();
            $table->string("ts_lvl6_start")->nullable();
            $table->string("ts_lvl6_end")->nullable();
            $table->string("ts_lvl7_start")->nullable();
            $table->string("ts_lvl7_end")->nullable();
            $table->string("ts_lvl8_start")->nullable();
            $table->string("ts_lvl8_end")->nullable();
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
        Schema::drop('mrant_games');
    }
}
