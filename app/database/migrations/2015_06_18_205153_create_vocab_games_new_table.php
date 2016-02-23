<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVocabGamesNewTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vocab_games_new', function(Blueprint $table)
        {
            $table->increments('id');
            $table->timestamps();
            $table->string("subject_id");
            $table->string("session_id")->nullable();
            $table->string("test_name")->nullable();
            $table->string("grade")->nullable();
            $table->date("dob")->nullable();
            $table->integer("age")->nullable();
            $table->integer("sex")->nullable();
            $table->dateTime("played_at")->nullable();
            $table->integer("score");
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('vocab_scores_new')) {
            Schema::drop('vocab_scores');
        }

        if (Schema::hasTable('vocab_games_new')) {
            Schema::drop('vocab_games');
        }
    }

}
