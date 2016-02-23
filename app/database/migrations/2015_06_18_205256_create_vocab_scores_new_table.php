<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVocabScoresNewTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vocab_scores_new', function(Blueprint $table)
        {
            $table->increments('id');
            $table->timestamps();
            $table->integer("game_id");
            $table->integer("card");
            $table->integer("value");
            $table->string("additional");
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
            Schema::drop('vocab_scores_new');
        }
    }

}
