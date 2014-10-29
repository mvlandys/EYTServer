<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotthisScoresTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('notthis_scores', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer("game_id");
            $table->foreign('game_id')->references('id')->on('notthis_games');
            $table->integer("set");
            $table->integer("rep");
            $table->integer("correct");
            $table->string("responseTime");
            $table->integer("attempted");
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
        Schema::drop('notthis_scores');
	}

}
